<?php

namespace Lib\Router;

use Closure;
use Lib\Router\Route;

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
    protected static function seperatePath(string $path): array
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
    protected function &getBranch(string $path, bool $popLast): array
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
            if (!array_key_exists($value, $branch)) {
                // Create new branch
                $branch[$value] = [];
            }
            $branch = &$branch[$value];
        }

        return $branch;
    }

    /**
     * Add middlewares
     * @param callable $middleware The middleware to add.
     * @return Middleware The middleware chain to allow adding middlewares.
     * @since 1.0.0
     */
    public function use(callable $middleware, $path = ''): Middleware
    {
        $this->getBranch($path, false)[] = $middleware;
        return $this;
    }

    /**
     * Add route
     * @param string $path The path of the route.
     * @param callable $callback The callback of the route.
     * @return MiddlewareChain The middleware adder to allow adding middlewares to the route.
     * @since 1.0.0
     * @todo Implement this function
     */
    public function route(string $path, callable $callback): Middleware
    {
        // popping the last element of the path since it's the route name
        $route = new Route($path, $callback);
        $this->getBranch($path, true)[] = $route;
        return new MiddlewareChain(function ($middleware) use ($route) {
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
     * Walks through the specified branch and executes the middlewares and routes.
     * @param array $branch The branch to walk through.
     * @param bool $executeRoutes Whether to execute the routes or not.
     * @since 1.0.0
     */
    public function walkBranch($branch, $executeRoutes = false): void
    {
        $branch = &$this->executionTree;
        foreach ($branch as $key => $value) {
            echo $key . "\n";
            if (is_numeric($key)) continue; // skip non-numeric keys (branches)

            // if the value is a route
            if ($value instanceof Route) {
                // skip if not executing routes
                if (!$executeRoutes)
                    continue;
                // execute middlewares
                foreach ($value->middlewares as $middleware) {
                    $middleware();
                }
            }

            // if the value is a middleware
            if (is_callable($value)) {
                $value(); // execute the middleware
            }
        }
    }

    /**
     * Run the router
     * @since 1.0.0
     * @todo Implement this function
     */
    public function run(): void
    {
        $path = $_SERVER['REQUEST_URI'];
        $path = self::seperatePath($path);
        $branch = &$this->executionTree;
        for ($i = 0; $i < count($path); $i++) {
            $value = $path[$i];
            $last = $i === count($path) - 1;
            if (array_key_exists($value, $branch)) {
                $branch = &$branch[$value];
                $this->walkBranch($branch, $last);
            }
        }
    }
}
