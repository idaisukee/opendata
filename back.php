<!DOCTYPE html>
<html>
	<head>
		<title>地図から路線検索 結果表示</title>
		<meta charset="utf-8">
<style>
	html,body{
		height:100%;
		margin:10px;
		padding:0;

	}

	div.path {
		margin-bottom: 5em;
		border: solid 2px;
	}

	table {
		margin: 2em;
		margin-top: 0em;
		padding-top: 0em;
	}

	th, td {
		border: solid 1px;
	}

	p {
		margin-left: 2em;
	}
</style>
	</head>
	<body>
		<h1>地図から路線検索 結果表示</h1>
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

			$radius = $g['radius'];
			$date = $g['date'];
			$time = $g['time'];

			print '<p>出発日時 '.$date.' '.$time.'</p>';
			print '<p>出発地点 ('.$start_lat.', '.$start_long.')</p>';
			print '<p>到着地点 ('.$end_lat.', '.$end_long.')</p>';
			print '<p>マーカーから '.$radius.' m 以内の駅・停留所を探索しました。</p>';
			print '<p>結果は、乗車時間の短い順に表示します。</p>';
			if (true === Main::validate($start_lat, $start_long, $end_lat, $end_long, $radius, $date, $time) ) {

				$p = Main::proc($start_lat, $start_long, $end_lat, $end_long, $radius, $date, $time);
				print '<p>結果は '.count($p).' 件です。';
				foreach ($p as $i => $path) {
					print $path;
				}
			} else {
				print 'validation error.';
			}

		?>

	</body>
</html>
