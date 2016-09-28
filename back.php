<?php
require 'main.php';
//require $_SERVER['DOCUMENT_ROOT'].'/open/main.php';

//require __DIR__.'/main.php';
//echo __DIR__;
$g = $_GET;
$start_json = $g['start'];
$end_json = $g['end'];
$start = json_decode($start_json, false);
$end = json_decode($end_json, false);
//print_r($start);
//print_r($end);

 $start_lat = $start->lat;
 $start_long =  $start->lng;
//echo $start_long;
 $end_lat =  $end->lat;
 $end_long = $end->lng;

 $radius = '300';

 $date = '20160603';
 $time = '0830';

$p = Main::proc($start_lat, $start_long, $end_lat, $end_long, $radius, $date, $time);
print_r($p);

$start_lat = '34.972937';
$start_long = '138.384326';

$end_lat = '34.972937';
$end_long = '138.394326';

$q  =Main::proc($start_lat, $start_long, $end_lat, $end_long, $radius, $date, $time);
//print_r($q);