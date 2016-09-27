<?php

require '../main.php';
$geo_point = '35.6783055555556,139.770441666667,tokyo,100';

$c = Main::stationCand($geo_point);
$t = Main::trimStationCand($c);
// print_r($t);

$geo_point = '34.972937,138.364326,wgs84,300';
$c = Main::stationCand($geo_point);
$t = Main::trimStationCand($c);
print_r($t);
