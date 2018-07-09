<?php

class Route
{
    private $route;
    
    public function __construct()
    {
        $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
        if(strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
        $uri = '/' . trim($uri, '/');
        $this->route = $uri;
    }
    
    public function getRoute()
    {
        return $this->route;
    }
    
    public function getRouteAsArray()
    {
        $routes = explode('/', $this->route);
        $cleanRoute = [];

        foreach($routes as $route)
        {
            if(trim($route) != '')
            {
                $cleanRoute[] = $route;
            }
        }
        
        return $cleanRoute;
    }
}