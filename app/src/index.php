<?php

/**
 * Main layout file
 */

use Core\Router\Context;
use Core\Router\Router;

require __DIR__ . '/vendor/autoload.php';

$router = new Router();

$router->route('/sa', function (Context $response): void {
    $response->setStatus(404);
});

$router->run();
