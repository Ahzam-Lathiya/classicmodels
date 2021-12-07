<?php

$redis = new \Redis();

$redis->connect('127.0.0.1', 6379);

$name = serialize('ahzam');
$redis->hSet('12345678', 'user', $name);

$name = serialize('lathiya');
$redis->hSet('12348765', 'user', $name);

$name = serialize('boy');
$redis->hSet('87654321', 'user', $name);

$age = serialize('24');
$redis->hSet('12345678', 'age', $age);

$gender = serialize('male');
$redis->hSet('12345678', 'gender', $gender);

/*
echo unserialize($redis->hGet('12345678', 'user')) . PHP_EOL;
echo unserialize($redis->hGet('12345678', 'age')) . PHP_EOL;
echo unserialize($redis->hGet('12345678', 'gender')) . PHP_EOL;
*/

echo json_encode($redis->keys("*")) . PHP_EOL;

?>
