<?php


require 'vendor/autoload.php';
use GuzzleHttp\Client;
$client = new Client([
	// Base URI is used with relative requests
	'base_uri' => 'http://api.ekispert.jp/v1/json/search/course/extreme?key=tdb4EgdcMq9GZ4A2&viaList=20288:20210&date=20160603&time=0830&searchType=arrival&language=jp',
	// You can set any number of default request options.
	'timeout'  => 2.0,
]);


$response = $client->request('GET');
echo $response->getBody();