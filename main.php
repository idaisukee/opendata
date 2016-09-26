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
		$response = $client->request('GET', $str, $param);
		return $response->getBody();
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
		$response = $client->request('GET', $str, $param);
		return $response->getBody();
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
		$response = $client->request('GET', $str, $param);
		return $response->getBody();
	}



	// not get json



	public function stationCandList($geo_point)
	{
		$json = self::stationCand($geo_point);
		$ar = json_decode($json, true);
		$points = $ar['ResultSet']['Point'];
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



}
//echo Main::stationCand('35.6783055555556,139.770441666667,00');h
$koyamachi = '34.972937,138.384326,tokyo,300';

$via_list = '887673:887754';
$date = '20160603';
$time = '0830';


//$a = [1, 2, 3];
//$b = [8, 9, 0];

$o = '23634';
$d = '887544';
$p = Main::path($via_list, $date, $time);
$ob = json_decode($p, false);
print_r($ob);

$a = [23634, 887673];
$b = [888033, 887544];

//$c = Util::direct_product($a, $b);
//print_r($c);
$e = Main::paths($a, $b, $date, $time);
//print_r($e);
//echo $p;
//$a = json_decode($p, false);
//print_r( $a);
//echo json_decode(Main::stationCand($koyamachi), false);
//print_r(Main::stationCandList($koyamachi));
