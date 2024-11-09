<?php

function getbits(array $bin, int $start = 0, int $end = null): string
{
	$sl = array_slice($bin, $start, $end);
	return implode("", $sl);
}
$rm_mod_cal = array("000" => "bx+si", "001" => "bx+di", "010" => "bp+si", "011" => "bp+di", "100" => "si", "101" => "di", "110" => "bp", "111" => "bx");

$addr16 = array("000" => "ax", "001" => "cx", "010" => "dx", "011" => "bx", "100" => "sp", "101" => "bp", "110" => "si", "111" => "di");
$addr8 = array("000" => "al", "001" => "cl", "010" => "dl", "011" => "bl", "100" => "ah", "101" => "ch", "110" => "dh", "111" => "bh");
$inst = array(
	"100010" => array(
		"name" => "mov",
		"func" => function (array &$bin) {
			global $addr16;
			global $addr8;
			global $rm_mod_cal;
			//assume the first 6 bits are done
			$remove = 16;
			$start = 5;
			$d = $bin[$start + 1];
			$w = $bin[$start + 2];
			$mov = getbits($bin, $start + 3, 2);
			$reg = getbits($bin, $start + 5, 3);
			$rm = getbits($bin, $start + 8, 3);
			$addr = [];
			if ($w == 1) {
				//high
				$addr = $addr16;
			} else {
				//low
				$addr = $addr8;
			}
			$dest = "";
			$src = "";
			$str = "";
			if ($d == 1) {
				$dest = $reg;
				$src = $rm;
			} else {
				$dest = $rm;
				$src = $reg;
			}
			if ($mov == "11") {
				echo "mov " . $addr[$dest] . ", " . $addr[$src] . "\n";
			} else {
				$disp = 0;
				$str = $rm_mod_cal[$src];
				if ($mov == "01") {
					$disp += 8;
					$str .= "+" . bindec(getbits($bin, $start + 11, 8));
				}
				if ($mov == "10") {
					$disp += 16;
					$str .= "+" . bindec(getbits($bin, $start + 11, 16));
				}
				$remove += $disp;
				echo "mov " . $addr[$dest] . ", [" . $str . "]\n";
			}
			array_splice($bin, 0, $remove);
		},

	),
	"1011" => array(
		"name" => "mov",
		"func" => function (array &$bin) {
			global $addr16;
			global $addr8;
			$start = 3;
			$w = $start + 1;
			$reg = getbits($bin, $start + 2, 3);
			$val = 0;
			$addr = [];
			if ($w = 0) {
				$val = bindec(getbits($bin, $start + 5, 16));
				$addr = $addr16;
				array_splice($bin, 0, 24);
			} else {
				$val = bindec(getbits($bin, $start + 5, 8));
				$addr = $addr8;
				array_splice($bin, 0, 16);
			}
			echo "mov " . $addr[$reg] . ", " . $val . "\n";
		}
	)
);
