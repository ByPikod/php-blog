<?php

/**
 * Main layout file
 */

// use Core\Router\Context;
use Lib\Testing\Test;

require __DIR__ . '/vendor/autoload.php';

Test::it(function (Test $test) {
    $test->assertEqual(1, 1);
});

/*
$router = new Router();

$router->route('/sa', function (Router $response): void {
    $response->setStatus(404);
});

$router->__test();
$router->run();
*/
