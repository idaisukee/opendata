<?php

require '../main.php';
$start_lat = '34.972937';
$start_long = '138.384326';

$end_lat = '34.972937';
$end_long = '138.394326';

$radius = '200';

$date = '20160603';
$time = '0830';

$m = Main::proc($start_lat, $start_long, $end_lat, $end_long, $radius, $date, $time);

print_r($m);
