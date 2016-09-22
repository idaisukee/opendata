<?php

require 'vendor/autoload.php';
require 'const.php';
use GuzzleHttp\Client;

class Main
{

	public function path()
	{
		$client = new Client([
			'base_uri' => 'http://api.ekispert.jp/v1/json/',
			'timeout'  => 2.0,
		]);

		$str = 'search/course/extreme?key='.KEY.'&viaList=20288:20210&date=20160603&time=0830&searchType=arrival&language=jp';
		$response = $client->request('GET', $str);
		echo $response->getBody();
	}

}
Main::path();

