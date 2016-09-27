<?php

require '../main.php';
$geo_point = '35.6783055555556,139.770441666667,tokyo,100';

$c = Main::stationCand($geo_point);
$t = Main::trimStationCand($geo_point);
print_r($t);
