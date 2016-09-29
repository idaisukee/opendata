<?php

require '../main.php';
$date = '20160603';
$time = '0830';
$via_list = '887668:8877000';
$p = Main::path($via_list, $date, $time);
$o = json_decode($p, false);
$t = Main::trimPath($o)[0];
print_r($t);
$u = Main::uptake_combined($t);
print_r($u);