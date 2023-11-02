<?php

namespace Lib\Router;

use Closure;
use Lib\Router\MiddlewareChain;

/**
 * MiddlewareAdder class created to be used by the router class.
 * Its returned when a route is created. It's used to add middlewares to the route.
 * Use method is a proxy to the callback passed to the constructor.
 * @since 1.0.0
 */
class MiddlewareAdder implements MiddlewareChain
{
    public Closure $callback;

    /**
     * MiddlewareAdder constructor.
     * @param Closure $callback The callback to be called when the use method is called.
     * @since 1.0.0
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * This method is a proxy to the callback passed to the constructor.
     * @param callable $middleware The middleware to be added to the route.
     * @return MiddlewareAdder The middleware adder itself to allow chaining.
     * @since 1.0.0
     */
    public function use(callable $middleware, $path = ''): MiddlewareChain
    {
        ($this->callback)($middleware, $path);
        return $this;
    }
}
