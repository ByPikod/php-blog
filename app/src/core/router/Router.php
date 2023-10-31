<?php

namespace Core\Router;

use Core\Router\RouteGroup;

/**
 * Router class
 * @since 1.0.0
 */
class Router
{
    private $routes = [];

    /**
     * Run the router
     * @since 1.0.0
     */
    public function run(): void
    {
        $path = $_SERVER['REQUEST_URI'];
    }

    public function route(string $path): void
    {
    }
}
