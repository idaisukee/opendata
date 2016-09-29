<?php
require 'main.php';

$s1 = '御門台';
$s1_c = Main::stationCode($s1);
echo $s1_c;
print "\n";

$s2 = '岩成不動';
$s2_c = Main::stationCode($s2);
echo $s2_c;
print "\n";


$s3 = '北安東三丁目';
$s3_c = Main::stationCode($s3);
echo $s3_c;
print "\n";

$s4 = '麻機北';
$s4_c = Main::stationCode($s4);
echo $s4_c;
print "\n";
