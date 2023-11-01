<?php

namespace Core\Router;

use Closure;
use Core\Router\Route;

/**
 * Router class
 * @since 1.0.0
 */
class Router implements RouteGroup
{
    protected array $executionTree = [];

    /**
     * Add middlewares
     * @param callable $middleware The middleware to add.
     * @return MiddlewareChain The middleware chain to allow adding middlewares.
     * @since 1.0.0
     */
    public function use(callable $middleware): MiddlewareChain
    {
        $this->executionTree[] = $middleware;
        return $this;
    }

    /**
     * Add route
     * @param string $path The path of the route.
     * @param callable $callback The callback of the route.
     * @return MiddlewareAdder The middleware adder to allow adding middlewares to the route.
     * @since 1.0.0
     */
    public function route(string $path, callable $callback): MiddlewareChain
    {
        $route = new Route($path, $callback);
        $this->executionTree[] = $route;
        return new MiddlewareAdder(function ($middleware) use ($route) {
            $route->middlewares[] = $middleware;
        });
    }

    /**
     * Add route group
     * @param string $path The path of the route group.
     * @return SubRouter The route group.
     * @since 1.0.0
     */
    public function group(string $path): RouteGroup
    {
        $group = new SubRouter(
            // called when a route is added
            function ($path, $callback) {
            },
            // called when a middleware is added
            function ($path) {
            },
            // called when have to add a route group
            function ($middleware) {
            }
        );
        $this->executionTree[] = $group;
        return $group;
    }

    /**
     * Run the router
     * @since 1.0.0
     */
    public function run(): void
    {
        $path = $_SERVER['REQUEST_URI'];
    }
}
