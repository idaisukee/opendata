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

		$via_list = '20288:20211';
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
		echo $response->getBody();
	}

}
Main::path();

