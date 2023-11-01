<?php

namespace Core\Router;

use Closure;
use Core\Router\Route;

/**
 * Router class
 * @since 1.0.0
 * @todo Implement this class
 */
class Router implements RouteGroup
{
    protected array $executionTree = [];

    /**
     * Seperate the path into an array of strings.
     * @param string $path The path to seperate.
     * @return array The array of strings.
     * @since 1.0.0
     */
    private static function seperatePath(string $path): array
    {
        $path = trim($path, '/');
        $path = explode('/', $path);
        return $path;
    }

    /**
     * Returns a branch from the execution tree.
     * @param string $path The path to get the branch from.
     * @param bool $shiftLast Whether to shift the last element of the path or not.
     * @return array The branch.
     * @since 1.0.0
     */
    private function getBranch(string $path, bool $popLast): array
    {
        // Seperate to parts
        $path = self::seperatePath($path);

        // Pop last element if needed
        if ($popLast) {
            array_pop($path);
        }

        // Get branch
        $branch = &$this->executionTree;
        foreach ($path as $value) {
            if (is_null($branch[$value])) {
                // Create new branch
                $branch[$value] = [];
            }
            $branch = $branch[$value];
        }

        return $branch;
    }

    /**
     * Add middlewares
     * @param callable $middleware The middleware to add.
     * @return MiddlewareChain The middleware chain to allow adding middlewares.
     * @since 1.0.0
     * @todo Implement this function
     */
    public function use(callable $middleware, $path = ''): MiddlewareChain
    {
        $this->getBranch($path, false)[] = $middleware;
        return $this;
    }

    /**
     * Add route
     * @param string $path The path of the route.
     * @param callable $callback The callback of the route.
     * @return MiddlewareAdder The middleware adder to allow adding middlewares to the route.
     * @since 1.0.0
     * @todo Implement this function
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
     * @todo Implement this function
     */
    public function group(string $path): RouteGroup
    {
        $group = new SubRouter(
            // called when a route is added
            // @todo Implement this function
            function ($path, $callback) {
            },
            // called when a middleware is added
            // @todo Implement this function
            function ($path) {
            },
            // called when have to add a route group
            // @todo Implement this function
            function ($middleware) {
            }
        );
        $this->executionTree[] = $group;
        return $group;
    }

    /**
     * Run the router
     * @since 1.0.0
     * @todo Implement this function
     */
    public function run(): void
    {
        $path = $_SERVER['REQUEST_URI'];
    }
}
