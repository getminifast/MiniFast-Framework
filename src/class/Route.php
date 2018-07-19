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

    public function fromFile(string $file, string $controllerDir, string $templateDir)
    {
        $vars = [];
        if(file_exists($file))
        {
            if(self::is_json($file))
            {
                $json = json_decode(file_get_contents($file), true);
                if(isset($json['routes']))
                {
                    $possibleRoutes = self::findRouteBySize($json['routes']);

                    if(sizeof($possibleRoutes) > 0)
                    {
                        $currentRoute = self::getRouteAsArray();
                        sizeof($currentRoute);
                        $newRoutes = $possibleRoutes;
                        $backup = [];

                        for($i = 0; $i < sizeof($currentRoute); $i++)
                        {
                            $backup = $newRoutes;
                            $newRoutes = self::findRouteByIndex($newRoutes, $i, $currentRoute[$i]);

                            if(sizeof($newRoutes) < 1)
                            {
                                $newRoutes = self::findRouteByVar($backup, $i);

                                if(isset($newRoutes[0]['var']))
                                {
                                    $vars[$newRoutes[0]['var'][1]] = $currentRoute[$i];
                                }

                                if(sizeof($newRoutes) > 0)
                                {
                                    if(sizeof($newRoutes) != 1)
                                    {
                                        throw new Exception("There is multiple corresponding routes for `$this->route`.");
                                    }
                                }
                                else
                                {
                                    throw new Exception("No corresponding route for `$this->route`.");
                                }
                            }
                        }

                        if(sizeof($newRoutes) == 1)
                        {
                            if(isset($newRoutes[0]['var']) and isset($newRoutes[0]['index']))
                            {
                                self::useRoute($newRoutes[0], $controllerDir, $templateDir, $vars);
                            }
                            else
                            {
                                self::useRoute($newRoutes[0], $controllerDir, $templateDir);
                            }
                        }
                    }
                    else
                    {
                        echo 'ok';
                        throw new Exception("No corresponding route for `$this->route`.");
                    }
                }
                else
                {
                    //                    var_dump($json);
                    throw new Exception("No route found in `$file`.");
                }
            }
            else
            {
                throw new Exception("`$file` is not a valide JSON.");
            }
        }
        else
        {
            throw new Exception("File `$file` not found.");
        }
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getRouteAsArray()
    {
        $route = trim($this->route, '/');
        $routes = explode('/', $route);
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

    public function getRouteAsJson()
    {
        return json_encode(self::getRouteAsArray());
    }

    public function is_json($file)
    {
        return json_last_error() == JSON_ERROR_NONE;
    }

    private function findRouteBySize(array $routes)
    {
        $newRoutes = [];
        $currentSize = sizeof(explode('/', trim($this->route, '/')));
        foreach($routes as $route)
        {
            $array = explode('/', trim($route['route'], '/'));
            sizeof($array);
            if(sizeof($array) == $currentSize) // /!\ Problème !!!
            {
                $newRoutes[] = $route;
            }
        }

        return $newRoutes;
    }

    private function findRouteByIndex(array $routes, int $index, string $key)
    {
        $newRoutes = [];
        $currentSize = sizeof(self::getRouteAsArray());
        foreach($routes as $route)
        {
            $array = explode('/', trim($route['route'], '/'));
            if($array[$index] === $key)
            {
                $newRoutes[] = $route;
            }
        }

        return $newRoutes;
    }

    private function findRouteByVar(array $routes, int $index)
    {
        $newRoutes = [];
        foreach($routes as $route)
        {
            $array = explode('/', trim($route['route'], '/'));
            if(self::is_var($array[$index]))
            {
                $route['var'] = self::is_var($array[$index]);
                $route['index'] = $index;
                $newRoutes[] = $route;
            }
        }

        return $newRoutes;
    }

    private function is_var(string $key)
    {
        $match = [];
        preg_match('`\{([^\}]+)\}`', $key, $match);

        return $match;
    }

    private function useRoute(array $route, string $controllerDir, string $templateDir, array $vars = [])
    {
        if(isset($route['controller']))
        {
            if($route['controller'] != null)
            {
                $controller = new Controller($controllerDir);
                $controller->useController($route['controller'], $vars);
            }
        }

        if(isset($route['view']))
        {
            if($route['view'] != null)
            {
                $view = new View($templateDir);
                $view->render($route['view']);
            }
        }
    }
}