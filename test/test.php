<?php

require '../main.php';


$start_lat = '34.972937';
$start_long = '138.384326';

$end_lat = '34.972937';
$end_long = '138.394326';

$radius = '300';

$date = '20160603';
$time = '0830';

$gs = Main::geoPoint($start_lat, $start_long, $radius);
$ge = Main::geoPoint($end_lat, $end_long, $radius);

$cs = Main::stationCand($gs);
$ce = Main::stationCand($ge);

$os = json_decode($cs, false);
$oe = json_decode($oe, false);
print_r($os);
print_r($oe);


