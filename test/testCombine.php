<?php

require '../main.php';

$a = [1, 2];
$b = ['a', 'b', 'c'];

$c = Main::combine($a, $b);
print_r($c);

$a = [1];
$b = ['a', 'b'];

$c = Main::combine($a, $b);
print_r($c);