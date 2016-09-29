<?php
require '../vendor/autoload.php';
use Underscore\Types\Arrays as _;
$a = [];
$a = _::push($a, 3);
echo $a;