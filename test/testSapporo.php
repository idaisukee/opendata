<?php
require '../main.php';

$start_geo_point = '43.069640195555,141.36700630188,wgs84,3000';

$end_geo_point = '43.083921908805,141.35181427002,wgs84,3000';

$start_station_cand = Main::stationCand($start_geo_point);
//print_r(json_decode($start_station_cand,false));
print_r(Main::stationCandList($start_geo_point));
print_r(Main::stationCandList($end_geo_point));