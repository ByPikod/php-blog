<?php

namespace Core\Router;

/**
 * MiddlewareChain interface
 * This is used to allow the router to add middlewares to routes and route groups.
 * @see MiddlewareAdder
 * @see RouteGroup
 * @since 1.0.0
 */
interface MiddlewareChain
{
    /**
     * Use a middleware for a route or a group of routes.
     * @since 1.0.0
     * @return MiddlewareAdder The middleware adder itself to allow chaining.
     */
    public function use(callable $middleware): MiddlewareChain;
}
