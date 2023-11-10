<?php

require_once 'autoload.php';

use Lib\Router\RouterTest;
use PHPTest\Test;

$router = new RouterTest();
Test::suiteClass($router);
