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




	public function combine(array $lines, array $points)
	{
		$count = count($points);
		$out = [];
		foreach ($points as $i => $point) {
			$start = $point;
			if (isset ($lines[$i])) {
				$line = $lines[$i];
			} else {
				$line = null;
			}
			array_push($out, [$start, $line]);
		}
		return $out;
	}
}
class HttpException extends Exception
{
	public function __construct($place = null)
	{
		parent::__construct('Http fail.'.$place, 0);
	}
}


class NullCandException extends Exception
{
	public function __construct()
	{
		parent::__construct('cand fail.', 0);
	}
}



class NullPathException extends Exception
{
	public function __construct()
	{
		parent::__construct('path fail.', 0);
	}
}



class Main
{

	const TIMEOUT = 12.0;

	public function proc($start_lat, $start_long, $end_lat, $end_long, $radius, $date, $time)
	{
		try {
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
			$formatted = self::format($trimmed);
			// return $trimmed;
			return $formatted;
		} catch (Exception $e) {
			echo $e->getMessage();
		} catch (NullPathException $e) {
			echo $e->getMessage();
		} catch (NullCandException $e) {
			echo $e->getMessage();
		} catch (HttpException $e) {
			echo $e->getMessage();
		}
		// return $paths;
	}



	// get json

	// 経路探索
	public function path($via_list, $date, $time)
	{
		$client = new Client([
			'base_uri' => 'http://api.ekispert.jp/v1/json/',
			'timeout'  => self::TIMEOUT,
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
		} catch (Exception $e) {
			throw new HttpException('path');
		}
		$json =  $response->getBody();
		$obj = json_decode($json, false);
		if (isset($obj->ResultSet->Course)) {
			return $json;
		} else {
			throw new NullPathException();
		}
	}



	public function station($name)
	{
		$client = new Client([
			'base_uri' => 'http://api.ekispert.jp/v1/json/',
			'timeout'  => self::TIMEOUT,
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
		} catch (Exception $e) {
			throw new HttpException('station');
		}
		return $response->getBody();
	}



	public function stationCand($geo_point)
	{
		$client = new Client([
			'base_uri' => 'http://api.ekispert.jp/v1/json/',
			'timeout'  => self::TIMEOUT,
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
		} catch (Exception $e) {
			throw new HttpException('cand');
		}
		$json = $response->getBody();
		$obj = json_decode($json, false);
		if (isset($obj->ResultSet->Point)) {
			return $json;
		} else {
			throw new NullCandException();
		}
	}



	// not get json



	public function stationCandList($geo_point)
	{
		$json = self::stationCand($geo_point);
		$ar = json_decode($json, true);
		$the_point = $ar['ResultSet']['Point'];
		$points_num = count($the_point);
		if (1 === $points_num) {
			$points = [$the_point];
		} else {
			$points = $the_point;
		}
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
		$the_course = $path_obj->ResultSet->Course;
		$coures_num = count($the_course);
		if (1 === $coures_num) {
			$courses = [$the_course];
		} else {
			$courses = $the_course;
		}
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
				$the_point = $course->Route->Point;
				$points_num = count($the_point);
				if (1 === $points_num) {
					$points = [$the_point];
				} else {
					$points = $the_point;
				}
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



	public function flatten($combined)
	{
		$str = '';
		foreach ($combined as $i => $pair) {
			$str .= $pair[0];
			$str .= $pair[1][0];
			$str .= $pair[1][1];
			$str .= $pair[1][2];
		}
		return $str;
	}



	public function format(array $trimmed_paths)
	{
		$out = [];
		foreach ($trimmed_paths as $i => $paths_with_point_pair) {
			$cell = [];
			foreach ($paths_with_point_pair as $j => $path) {
				$price = $path[0];
				$time = (int) $path[1] + (int) $path[2];
				$lines = $path[3];
				$points = $path[4];

				$combined = Util::combine($lines, $points);
				$flattend = self::flatten($combined);
				$str
					=
					$price.' '.$time."\n".$flattend;
				array_push($cell, $str);
			}
			array_push($out, $cell);
		}
		// return $combined;
		return $out;
	}


	public function trimStationCand($station_cand)
	{
		$station_cand_obj = json_decode($station_cand, false);
		$the_point = $station_cand_obj->ResultSet->Point;
		if (isset($the_point)) {
			$points_num = count($the_point);
			if (1 === $points_num) {
				$points = [$the_point];
			} else {
				$points = $the_point;
			}
			$out = [];
			foreach ($points as $i => $point) {
				$code = $point->Station->code;
				$distance = $point->Distance;
				$out[$i] = [$code, $distance];
			}
		} else {
			throw new Exception('cand is null.');
		}
		// return $out;
		return $station_cand_obj;
	}



	public function geoPoint($lat, $long, $radius)
	{
		$geo_point = $lat.','.$long.','.'wgs84'.','.$radius;
		return $geo_point;
	}
}
