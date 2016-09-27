<?php

require '../main.php';
$start_lat = '34.972937';
$start_long = '138.384326';

$end_lat = '34.972937';
$end_long = '138.394326';

$radius = '300';

$date = '20160603';
$time = '0830';



 function proc($start_lat, $start_long, $end_lat, $end_long, $radius, $date, $time)
	{
		$start_geo_point = self::geoPoint($start_lat, $start_long, $radius);
		$start_station_cand = self::stationCand($start_geo_point);
		$start_trimmed = self::trimStationCand($start_station_cand);
		$start_list = self::stationCandList($start_geo_point);

		$end_geo_point = self::geoPoint($end_lat, $end_long, $radius);
		$end_station_cand = self::stationCand($end_geo_point);
		$end_trimmed = self::trimStationCand($end_station_cand);
		$end_list = self::stationCandList($end_geo_point);

		$paths = self::paths($start_list, $end_list, $date, $time);

		$trimmed = self::trimPaths($paths);
		// return $trimmed;
		return $paths;
	}

$paths = proc($start_lat, $start_long, $end_lat, $end_long, $radius, $date, $time);
print_r($paths);