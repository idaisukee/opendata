<!DOCTYPE html>
<html>
	<head>
		<title>Simple Map</title>
		<meta charset="utf-8">
	</head>
	<body>

		<?php
			require 'main.php';
			$g = $_GET;
			$start_json = $g['start'];
			$end_json = $g['end'];
			$start = json_decode($start_json, false);
			$end = json_decode($end_json, false);
			$start_lat = $start->lat;
			$start_long =  $start->lng;
			$end_lat =  $end->lat;
			$end_long = $end->lng;

			$radius = '300';

			$date = '20160603';
			$time = '0830';

			if (true === Main::validate($start_lat, $start_long, $end_lat, $end_long, $radius, $date, $time) ) {

			} else {
				print 'validation error.';
			}

		?>

	</body>
</html>
