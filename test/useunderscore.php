<?php
require 'vendor/autoload.php';

use Underscore\Types\Arrays as _;

$ar = [1, 2];
$arr = _::append($ar, 3);
print_r( $arr);
