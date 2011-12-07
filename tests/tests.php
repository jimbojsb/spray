<?php
require_once '../src/Spray/Wrapper.php';



Spray\Wrapper::init();



$response = new Spray\Response();
$response->setStatusCode(\Spray\Response::STATUS_302);
$response->setHeader('Location:', 'http://www.bing.com/');
Spray\Wrapper::setResponse($response);

$response = file_get_contents('http://www.google.com/');
echo $response . PHP_EOL;

Spray\Wrapper::reset();

echo $response . PHP_EOL;


