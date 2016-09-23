<?php

require 'vendor/autoload.php';
require 'const.php';
use GuzzleHttp\Client;

class Main
{

	// 経路探索
	public function path()
	{
		$client = new Client([
			'base_uri' => 'http://api.ekispert.jp/v1/json/',
			'timeout'  => 2.0,
		]);

		$via_list = '887673:887754';
		$date = '20160603';
		$time = '0830';

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
 Main::path();
 Main::stationNumber('城東町');
 Main::station('城東町');
echo  Main::stationCode('');