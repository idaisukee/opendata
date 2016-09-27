<?php

require '../main.php';

$via_list = '889583:888323';
$date = '20160603';
$time = '0830';

$p = Main::path($via_list, $date, $time);
$o = json_decode($p, false);
$q = $o->ResultSet->Course[0]->Route->Line;
echo count($q);
echo get_class($q);
print_r($q);