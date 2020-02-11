<?php namespace GuruBob;

use GuruBob\AsciiTable\InvalidFormatException;

/**
*  A sample class
*
*  Use this section to define what this class is doing, the PHPDocumentator will use this
*  to automatically generate an API documentation using this information.
*
*  @author yourname
*/
class AsciiTable {

	protected $output;

	protected $headers = [];
	protected $rows = [];
	protected $format = 'box';	// default option
	protected $only = [];
	protected $except = [];

	protected $formats = [
		'box' => [
			'br' => '┘',    // Bottom right
			'tr' => '┐',    // Top right
			'tl' => '┌',    // Top left
			'bl' => '└',    // Bottom left
			'cross' => '┼', // Cross
			'hl' => '─',    // Horizontal line
			'lhl' => '├',   // Left horizontal line
			'rhl' => '┤',   // Right horizontal line
			'bvl' => '┴',   // Bottom vertical line
			'tvl' => '┬',   // Top vertical line
			'vl' => '│'     // Vertical line
		],
		'doublebox' => [
			'br' => '╝',    // Bottom right
			'tr' => '╗',    // Top right
			'tl' => '╔',    // Top left
			'bl' => '╚',    // Bottom left
			'cross' => '╬', // Cross
			'hl' => '═',    // Horizontal line
			'lhl' => '╠',   // Left horizontal line
			'rhl' => '╣',   // Right horizontal line
			'bvl' => '╩',   // Bottom vertical line
			'tvl' => '╦',   // Top vertical line
			'vl' => '║'     // Vertical line
		],
		'ascii' => [
			'br' => '+',    // Bottom right
			'tr' => '+',    // Top right
			'tl' => '+',    // Top left
			'bl' => '+',    // Bottom left
			'cross' => '+', // Cross
			'hl' => '-',    // Horizontal line
			'lhl' => '+',   // Left horizontal line
			'rhl' => '+',   // Right horizontal line
			'bvl' => '+',   // Bottom vertical line
			'tvl' => '+',   // Top vertical line
			'vl' => '|'     // Vertical line
		]
	];


	/**
	* Sample method
	*
	* Always create a corresponding docblock for each method, describing what it is for,
	* this helps the phpdocumentator to properly generator the documentation
	*
	* @param string $param1 A string containing the parameter, do this for each parameter to the function, make sure to make it descriptive
	*
	* @return string
	*/

	public function __construct($headers = [], $rows = [], $format = '') {

		// If headers values are arrays not strings then assume that the data
		// passed in is a bunch of key/value pair records, e.g. what you might
		// get from the results of doing a database query.
		if(isset($headers[0]) and is_array($headers[0])) {
			$this->headers(array_keys($headers[0]));
			$this->rows(array_map(function($data){
				return array_values($data);
			}, $headers));
		} else if(isset($headers[0]) and is_string($headers[0])) {
			// Normal behaviour, headers are a bunch of strings
			if($headers) $this->headers($headers);
			if($rows) $this->rows($rows);
		} else {
			if($headers) {
				throw new InvalidFormatException('Unexpected format for headers, please pass an array of strings or an array of arrays if you have a collection of things');
			}
		}
		if($format) $this->format($format);
	}

	public function getHeaders() {
		return $this->headers;
	}

	public function headers($headers) {
		$this->headers = $headers;
		return $this;
	}

	public function getRows() {
		return $this->rows;
	}

	public function rows($rows) {
		$this->rows = $rows;
		return $this;
	}

	public function addRow($row) {
		$this->rows[] = $row;
		return $this;
	}

	public function getFormat() {
		return $this->format;
	}

	public function only($fields) {
		$this->except = null;
		$this->only = (array)$fields;
		return $this;
	}

	public function except($fields) {
		$this->only = null;
		$this->except = (array)$fields;
		return $this;
	}

	public function format($format) {
		if(!isset($this->formats[$format])) {
			throw new InvalidFormatException('Unknown format ['.$format.'] for AsciiTable');
		}
		$this->format = $format;
		return $this;
	}

	public function copy() {
		return clone($this);
	}

	public function __toString() {

		$headers = $this->headers;
		$rows = $this->rows;
		$format = $this->format;

		// No rows, no output
		if(!$rows) return '';

		if($this->only) {
			// Get the indexes of where the 'only' fields are:
			$onlyIndexes = [];
			foreach($headers as $idx => $header) {
				if(!in_array($header, $this->only)) {
					$onlyIndexes[] = $idx;
				}
			}
			// Now remove those indexes from the headers:
			foreach($onlyIndexes as $idx) {
				unset($headers[$idx]);
			}
			// And zero base the headers:
			$headers = array_values($headers);
		
			// Now do the same for the rows:
			foreach($rows as $rowIdx => $row) {
				foreach($onlyIndexes as $idx) {
					unset($rows[$rowIdx][$idx]);
				}
				// And zero base the row:
				$rows[$rowIdx] = array_values($rows[$rowIdx]);
			}
		}
		
		if($this->except) {
			// Get the indexes of where the 'except' fields aren't:
			$exceptIndexes = [];
			foreach($headers as $idx => $header) {
				if(in_array($header, $this->except)) {
					$exceptIndexes[] = $idx;
				}
			}
			// Now remove those indexes from the headers:
			foreach($exceptIndexes as $idx) {
				unset($headers[$idx]);
			}
		
			// Now do the same for the rows:
			foreach($rows as $rowIdx => $row) {
				foreach($exceptIndexes as $idx) {
					unset($rows[$rowIdx][$idx]);
				}
			}

			// Zero base the header and rows
			$headers = array_values($headers);
			foreach($rows as $idx => $row) $rows[$idx] = array_values($row);
		}

		$chars = $this->formats[$format];

		// Calculate width of columns
		$columns = [];
		if($this->headers) {
			foreach($headers as $i => $header) {
				$columns[$i] = strlen($header);
			}
		}

		foreach($rows as $row) {
			foreach($row as $i => $data) {
				$columns[$i] = max(@$columns[$i], strlen($data));
			}
		}

		// Begin output
		ob_start();

		// Table top line
		echo $chars['tl'];
		foreach($columns as $col => $width) echo str_repeat($chars['hl'], $width+2).($col == count($columns)-1 ? $chars['tr'] : $chars['tvl']);
		echo "\n";

		if($this->headers) {
			// Table headers
			foreach($columns as $col => $width) echo $chars['vl']." ".sprintf("%-{$width}s", $headers[$col])." "; echo $chars['vl']."\n";

			// Header/body separator
			foreach($columns as $col => $width) echo ($col == 0 ? $chars['lhl'] : $chars['cross']).$chars['hl'].str_repeat($chars['hl'], $width).$chars['hl']; echo $chars['rhl']."\n";
		}

		// Table data
		foreach($rows as $row) {
			foreach($row as $col => $data) echo $chars['vl']." ".sprintf("%-{$columns[$col]}s", $data)." "; echo $chars['vl']."\n";
		}

		// Table bottom line
		echo $chars['bl'];
		foreach($columns as $col => $width) echo str_repeat($chars['hl'], $width+2).($col == count($columns)-1 ? $chars['br'] : $chars['bvl']);
		echo "\n";

		return ob_get_clean();
	}
}
//
