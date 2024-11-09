<?php
require "inst.php";
require "read.php";
$file = $argv[1];
$registers = array("ax" => array_fill(0, 16, 0), "bx" => array_fill(0, 16, 0), "cx" => array_fill(0, 16, 0), "dx" => array_fill(0, 16, 0));
// $a = "100010"."0"."1"."01"."101"."010"."10011001";//mov d w mod reg r/m displo disphi
// $arr=array_map("intval",str_split($a.
// "1011"."1"."010". "01010100"."01010100"//mov w reg data data
//
// ));
$arr = readF($file);
$mod; // register operation (0b11) or a memory operation, 2 bit size
$rec; //3 bit size, register name/bitadress
$rm; //3 bit size, register in case mod is registry operation, or memory location(or somekinda other thing? cause he saidf memory operation) in case mod is memory operation
$d; //direction? basically it says if the destination reg is in rec or rm, in case mod is registry op
$w; //if the registrs we're working with are 8 or 16 bits?????? but isnt htis specific to mov operation????? i mean it could work in other ops


while (count($arr) > 0) {
	$bool = false;
	foreach ($inst as $key => $val) {
		$firstbit = getbits($arr, 0, strlen($key));
		if ($firstbit != $key) continue;
		$val["func"]($arr);
		$bool = true;
		break;
	}
	if (!$bool) {
		$isEmpty = array_search(1, $arr);
		if ($isEmpty === false) {
			echo "end of file\n";
			break;
		}
		echo "instruction unkown\n";
		$remaining = implode($arr);
		echo "remaining: $remaining\n";
		break;
	}
}
