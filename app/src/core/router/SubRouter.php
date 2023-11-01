<?php

namespace Core\Router;

use Closure;

/**
 * This is a mirror of Router with the functions redirected to the parent router.
 * @since 1.0.0
 */
class SubRouter implements RouteGroup
{
    private Closure $cb_route;
    private Closure $cb_group;
    private Closure $cb_use;

    public function __construct(
        Closure $cb_route,
        Closure $cb_group,
        Closure $cb_use
    ) {
        $this->cb_route = $cb_route;
        $this->cb_group = $cb_group;
        $this->cb_use = $cb_use;
    }

    public function use(Closure $middleware): MiddlewareChain
    {
        ($this->cb_use)($middleware);
        return $this;
    }

    public function route(string $path, Closure $callback): MiddlewareChain
    {
        ($this->cb_route)($path, $callback);
        return $this;
    }

    public function group(string $path): RouteGroup
    {
        ($this->cb_group)($path);
        return $this;
    }
}
