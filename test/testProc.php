<?php

require '../main.php';
$lat = '34.972937';
$long = '138.384326';
$radius = '300';
$m = Main::proc($lat, $long, $radius);

print_r($m);
