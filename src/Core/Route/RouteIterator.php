<?php

namespace MiniFast\Core\Route;

use \MiniFast\Core\Iterable;

class RouteIterator extends Iterable
{
    /**
     * Add a route
     * @param Route $route The section to add
     */
    public function add(Route $route)
    {
        $this->elems[] = $route;
    }
}
