<?php

namespace Core\Router;

use Core\Router\RouteGroup;

/**
 * Router class
 * @since 1.0.0
 */
class Router extends RouteGroup
{
    /**
     * Run the router
     * @since 1.0.0
     */
    public function run(): void
    {
        $path = $_SERVER['REQUEST_URI'];
    }
}
