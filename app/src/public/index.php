<?php

require __DIR__ . '/../vendor/autoload.php';

use Lib\Router\Router;

$router = new Router();
$router->route('/', function () {
    echo "Hello World";
});

$router->run();
