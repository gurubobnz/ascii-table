<?php
use PHPUnit\Framework\TestCase;
use GuruBob\AsciiTable;
use GuruBob\AsciiTable\InvalidFormatException;

/**
*  Corresponding Class to test YourClass class
*
*  For each class in your library, there should be a corresponding Unit-Test for it
*  Unit-Tests should be as much as possible independent from other test going on.
*
*  @author yourname
*/
class YourClassTest extends TestCase
{

	/**
	* Just check if the YourClass has no syntax error
	*
	* This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
	* any typo before you even use this library in a real project.
	*
	*/
	public function testIsThereAnySyntaxError()
	{
		$var = new AsciiTable;
		$this->assertTrue(is_object($var));
		unset($var);
	}

	/**
	* Just check if the YourClass has no syntax error
	*
	* This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
	* any typo before you even use this library in a real project.
	*
	*/
	public function testHeaders()
	{
		$table = new AsciiTable;
		$table->headers(['A','B','C']);
		$this->assertTrue(count($table->getHeaders()) == 3);
		unset($table);
	}

	public function testRows() {
		$table = new AsciiTable;
		$table->rows([1,2,3]);
		$this->assertTrue(count($table->getRows()) == 3);
		unset($table);
	}

	public function testCopy() {
		// Check copy doesn't create referenced object
		$table = new AsciiTable;
		$table2 = $table->copy();
		$table->headers(['A']);
		$table2->headers(['B']);
		$this->assertTrue($table->getHeaders()[0] <> $table2->getHeaders()[0]);
		unset($table);
	}

	public function testSettingInvalidFormatThrowsException() {
		$table = new AsciiTable;
		try {
			$table->format('ohdflgkhjdfgjhdfgjhsdlfgjhsdflgjkdsfhgkljdf');
			$thrown = false;
		} catch(InvalidFormatException $e) {
			$thrown = true;
		}
		$this->assertTrue($thrown);
	}

	public function testCreatingTableWithACollection() {
		$row = ['Name' => 'Bob', 'Computer' => 'Atari 130XE'];
		$data = [
			$row, $row, $row, $row
		];
		$table = new AsciiTable($data);
		$this->assertTrue(count($table->getRows()) == 4);
	}

	public function testCreatingTableWithInvalidCollection() {
		try {
			$data = [true, true, true];
			$table = new AsciiTable($data);
			$thrown = false;
		} catch(InvalidFormatException $e) {
			$thrown = true;
		}
		$this->assertTrue($thrown);
	}


}
