<?php

require '../main.php';
$geo_point = '43.068699667379995,141.35052680969238,wgs84,1000';
$c = Main::stationCand($geo_point);
$o = json_decode($c, false);
print_r($o);

$geo_point = '34.972937,138.384326,wgs84,1000';
$c = Main::stationCand($geo_point);
$o = json_decode($c, false);
print_r($o);

$geo_point = '35.6783055555556,139.760441666667,tokyo,100';
$c = Main::stationCand($geo_point);
$o = json_decode($c, false);
print_r($o);

