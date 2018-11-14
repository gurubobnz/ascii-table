<?php
	require 'vendor/autoload.php';

	echo<<<EOT
Simple ASCII Table Generator
============================

Features
--------

* Create tables suitable for CLI output quickly
* No external dependencies

View/run example.php for examples. This file is the output of that script:

```

EOT;

	$sampleData = [
		['Bob Brown', 'New Zealand'],
		['Wolfgang Puck', 'America'],
		['Winston Churchill', 'England']
	];

	echo "Create table via constructor:\n";
	echo new GuruBob\AsciiTable(
		['Name', 'Country'],	// Headers
		$sampleData
		// Optional format not specified (could be box or ascii)
	);

	echo "\nCreate table via OO interfaces:\n";
	$table = new GuruBob\AsciiTable();
	$table->headers(['Sales Agent', 'Travelling To']);
	$table->rows($sampleData);
	echo $table;

	echo "\nOutput same table with different headers (reusing defined table):\n";
	echo $table->headers(['Favourite Person', 'County Of Residence']);

	echo "\nASCII borders instead of box:\n";
	echo $table->copy()->format('ascii');
	echo "Note: Made a copy() so that the following header would retain box format.\n";

	echo "\nTable with no headers:\n";
	echo $table->headers(null);

	echo "\nCreate by chaining setters and adding individual rows:\n";
	echo (new GuruBob\AsciiTable(['Product', 'Price']))
		->addRow(['Apples', '$1.29'])
		->addRow(['Bananas', '$1.69'])
		->addRow(['Cherries', '$2.99'])
		->format('doublebox');

	echo "\n```\n";
