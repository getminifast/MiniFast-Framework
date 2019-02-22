<?php

namespace MiniFast\Core\Route;

use \MiniFast\Core\IterableIterator as IT;

class RouteIterator extends IT
{
    /**
     * Add a route
     * @param Route $route The section to add
     */
    public function add(Route $route)
    {
        $this->addItem($route);
    }
}
