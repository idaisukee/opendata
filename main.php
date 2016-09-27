<?php

require 'vendor/autoload.php';
require 'const.php';
use GuzzleHttp\Client;
use Underscore\Types\Arrays as _;


class Util
{
	public function direct_product($array1, $array2)
	{
		$prod = [];
		$k = 0;
		foreach ($array1 as $a => $b) {
			foreach ($array2 as $c => $d) {
				$cell =  [$b, $d];
				$prod[$k] = $cell;
				$k++;
			}
		}
		return $prod;
	}
}

class Main
{

	public function proc($start_lat, $start_long, $end_lat, $end_long, $radius, $date, $time)
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
		return $trimmed;
		// return $paths;
	}



	// get json

	// 経路探索
	public function path($via_list, $date, $time)
	{
		$client = new Client([
			'base_uri' => 'http://api.ekispert.jp/v1/json/',
			'timeout'  => 2.0,
		]);
		$str
			=
			'search/course/extreme';
		$param = [
			'query' => [
				'key' => KEY,
				'viaList' => $via_list,
				'date' => $date,
				'time' => $time,
				'language' => 'jp',
			],
		];
		try {
			$response = $client->request('GET', $str, $param);
			return $response->getBody();
		} catch (Exception $e) {
			echo 'err at path', $e->getMessage(), "\n";
		}
	}



	public function station($name)
	{
		$client = new Client([
			'base_uri' => 'http://api.ekispert.jp/v1/json/',
			'timeout'  => 2.0,
		]);

		$str = 'station';

		$param = [
			'query' => [
				'key' => KEY,
				'name' => $name,
				'language' => 'jp',
			],
		];
		try {
			$response = $client->request('GET', $str, $param);
			return $response->getBody();
		} catch (Exception $e) {
			echo 'err', $e->getMessage(), "\n";
		}
	}



	public function stationCand($geo_point)
	{
		$client = new Client([
			'base_uri' => 'http://api.ekispert.jp/v1/json/',
			'timeout'  => 2.0,
		]);
		$str = 'geo/station';
		$param = [
			'query' => [
				'key' => KEY,
				'geoPoint' => $geo_point,
			]
		];
		try {
			$response = $client->request('GET', $str, $param);
			return $response->getBody();
		} catch (Exception $e) {
			echo 'err at stationCand', $e->getMessage(), "\n";
		}
	}



	// not get json



	public function stationCandList($geo_point)
	{
		$json = self::stationCand($geo_point);
		$ar = json_decode($json, true);
		$the_points = $ar['ResultSet']['Point'];
		$list = [];
		foreach ($points as $k => $point) {
			array_push($list, $point['Station']['code']);
		}
		return $list;
	}



	public function paths($depart_list, $dest_list, $date, $time)
	{
		$prod = Util::direct_product($depart_list, $dest_list);
		$paths = [];
		foreach ($prod as $k => $cell) {
			$via_list = implode($cell, ':');
			$path_json = self::path($via_list, $date, $time);
			$path_obj = json_decode($path_json, false);
			$paths[$k] = $path_obj;
		}
		return $paths;
	}


	public function stationCode($name)
	{
		$json = self::station($name);
		$obj = json_decode($json, false);
		if ($obj->ResultSet->max === '1') {
			$code = $obj->ResultSet->Point->Station->code;
			return $code;
		} else {
			return 'many';
		}
	}



	public function stationNumber($name)
	{
		$json = self::station($name);
		$obj = json_decode($json, false);
		$number = $obj->ResultSet->max;
		return $number;
	}



	public function trimPath($path_obj)
	{
		$courses = $path_obj->ResultSet->Course;
		$out = [];
		foreach ($courses as $i => $course) {
			$price = $course->Price[0]->Oneway;
			$time_other = $course->Route->timeOther;
			$time_on_board = $course->Route->timeOnBoard;
			$the_line = $course->Route->Line;
			$lines_num = count($the_line);
			if (1 === $lines_num) {
				$lines = [$the_line];
			} else {
				$lines = $the_line;
			}
			$out_lines = [];
			foreach ($lines as $j => $line) {
				$name = $line->Name;
				$arrival_datetime = $line->ArrivalState->Datetime->text;
				$departure_datetime = $line->DepartureState->Datetime->text;
				$out_lines[$j] = [$name, $arrival_datetime, $departure_datetime];
				$points = $course->Route->Point;
				$out_points = [];
				foreach ($points as $k => $point) {
					$point_name = $point->Station->Name;
					$out_points[$k] = $point_name;
				}
				$out[$i] = [$price, $time_other, $time_on_board, $out_lines, $out_points];
			}
		}
		return $out;

		// return $courses;
	}



	public function trimPaths(array $paths)
	{
		$out = array_map('self::trimPath', $paths);
		return $out;
	}



	public function trimStationCand($station_cand)
	{
		$station_cand_obj = json_decode($station_cand, false);
		$points = $station_cand_obj->ResultSet->Point;
		$out = [];
		foreach ($points as $i => $point) {
			$code = $point->Station->code;
			$distance = $point->Distance;
			$out[$i] = [$code, $distance];
		}
		return $out;
	}



	public function geoPoint($lat, $long, $radius)
	{
		$geo_point = $lat.','.$long.','.'wgs84'.','.$radius;
		return $geo_point;
	}
}
