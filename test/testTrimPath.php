<?php

require '../main.php';
$date = '20160603';
$time = '0830';
$via_list = '887668:887759';
$p = Main::path($via_list, $date, $time);
$o = json_decode($p, false);
print_r(Main::trimPath($o));