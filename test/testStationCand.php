<?php

require '../main.php';
$geo_point = '35.6783055555556,139.770441666667,tokyo,100';
$c = Main::stationCand($geo_point);
$o = json_decode($c, false);
print_r($o);