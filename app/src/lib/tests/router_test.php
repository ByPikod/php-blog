<?php

require_once 'autoload.php';

use Lib\Router\RouterTest;
use Lib\Testing\Test;

$router = new RouterTest();
Test::suiteClass($router);
