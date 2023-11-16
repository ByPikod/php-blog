<?php

require __DIR__ . '/../vendor/autoload.php';

use PHPRouter\Router;
use PHPRouter\Context;

$router = new Router();
$router->route('/', function (Context $ctx) {
    echo "Hello World";
});

$router->route('/public', function (Context $ctx) {
    echo "This is public";
});

$router->use(function (Context $ctx) {
    echo "404 Not Found";
    $ctx->next();
});

$router->run();
