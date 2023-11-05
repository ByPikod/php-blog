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
        $path = array_filter($path, function ($value) {
            return $value !== '';
        });
        return $path;
    }

    /**
     * Returns a branch from the execution tree.
     * @param string $path The path to get the branch from.
     * @param bool $popLast Whether to pop the last element of the path or not.
     * @return array The branch.
     * @since 1.0.0
     */
    protected function &getBranch(string $path): array
    {
        // Seperate to parts
        $path = self::seperatePath($path);

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
        $this->getBranch($path)[] = $middleware;
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
        $route = new Route($path, $callback);
        $this->getBranch($path)[] = $route;
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
     * Walks through the specified branch and returns the middlewares and routes.
     * @param array $branch The branch to walk through.
     * @param Context $ctx The context to pass to the middlewares and routes.
     * @param bool $routes Whether to return the routes or not.
     * @return array The middlewares and routes.
     * @since 1.0.0
     */
    private static function getExecutables(array $branch, $routes = false): array
    {
        $executables = [];
        foreach ($branch as $key => $value) {
            if (!is_numeric($key)) continue; // skip non-numeric keys (branches)

            // if the value is a route
            if ($value instanceof Route) {
                // skip if not executing routes
                if (!$routes)
                    continue;
                // execute middlewares
                foreach ($value->middlewares as $middleware) {
                    $executables[] = $value;
                }
                $executables[] = $value->callback;
            }

            // if the value is a middleware
            if (is_callable($value)) {
                $executables[] = $value;
            }
        }
        return $executables;
    }

    /**
     * Executes the execution tree.
     * @param string $path The path to execute.
     * @since 1.0.0
     */
    private function executeTree(string $path): void
    {
        $pathArray = self::seperatePath($path);
        $branch = $this->executionTree;

        // Get executables ordered
        $executables = self::getExecutables($branch, (count($pathArray) === 0));
        for ($i = 0; $i < count($pathArray); $i++) {
            $value = $pathArray[$i];
            // if there is a branch with the specified key
            if (array_key_exists($value, $branch)) {
                $branch = $branch[$value];
                $last = $i >= count($pathArray) - 1;
                $executables = $executables + self::getExecutables($branch, $last);
            }
        }

        // If no executables found, return 404
        if (count($executables) === 0) {
            echo "404 Not Found\n";
            http_response_code(404);
            return;
        }

        // Execute executables
        $i = 1;
        $ctx = new Context(function ($ctx) use (&$executables, &$i) {
            // this scope is called when the next() function is called in previous executable
            $executables[$i]($ctx); // execute the next executable
            $i++; // increment the index
        });
        $executables[0]($ctx); // execute the first executable
    }

    /**
     * Run the router
     * @since 1.0.0
     * @todo Implement this function
     */
    public function run(): void
    {
        $path = $_SERVER['REQUEST_URI'];
        $this->executeTree($path);
    }
}
