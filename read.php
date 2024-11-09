<?php
function readF(string $filename): array
{
	if (!file_exists($filename)) {
		die("File not found.");
	}
	// Open the binary file for reading
	$bit_arr = [];
	$file = fopen($filename, 'rb');

	if (!$file) {
		die("Failed to open the file.");
	}

	// Read the file in chunks (for example, 1 byte at a time)
	while (!feof($file)) {
		// Read one byte
		$byte = fread($file, 1);
		if ($byte === false) {
			break; // Exit the loop if there's an issue reading
		}

		// Convert the byte to an integer
		$value = ord($byte);

		// Access individual bits using bitwise operations
		for ($i = 7; $i >= 0; $i--) {
			// Extract each bit by shifting and masking
			$bit = ($value >> $i) & 1;
			$bit_arr[] = $bit;
		}
	}

	// Close the file
	fclose($file);
	return $bit_arr;
}
