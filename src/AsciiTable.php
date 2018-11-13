<?php namespace GuruBob;

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
	protected $format = 'box';

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
		if($headers) $this->headers($headers);
		if($rows) $this->rows($rows);
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

	public function format($format) {
		$this->format = $format;
		return $this;
	}

	public function copy() {
		return new static($this->headers, $this->rows, $this->format);
	}

	public function __toString() {

		$headers = $this->headers;
		$rows = $this->rows;
		$format = $this->format;

		$formats = [
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

		$chars = $formats[$format];

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