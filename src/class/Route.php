<?php

class Route
{
    private $route;
    private $routeToUse;
    private $default;
    private $vars = [];
    private $controllerDir = [];
    private $controllers = [];
    private $templateDir;

    public function __construct()
    {
        $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
        if(strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
        $uri = '/' . trim($uri, '/');
        $this->route = $uri;
    }
    
    public function fromFile($file, $controllerDir = '', string $templateDir = '')
    {
        if(!empty($controllerDir))
        {
            if(is_array($controllerDir))
            {
                $this->controllerDir = array_merge($this->controllerDir, $controllerDir);
            }
            elseif(is_string($controllerDir))
            {
                $this->controllerDir[] = $controllerDir;
            }
        }
        
        if(!empty($templateDir))
        {
            $this->templateDir = $templateDir;
        }
        
        // If there are multiple routing files, check all files
        if(is_array($file))
        {
            foreach($file as $f)
            {
                if(is_string($f))
                {
                    // Does the file exists?
                    if(file_exists($f))
                    {
                        self::fromFile($f);
                    }
                }
            }
        }
        elseif(is_string($file))
        {
            // Does the file exists?
            if(file_exists($file))
            {
                $routes = json_decode(file_get_contents($file), true);

                if($routes === null)
                {
                    die("$file is not a valid JSON." . PHP_EOL);
                }
                else
                {
                    // If all seems ok, start parsing
                    if(1)
//                    if(sizeof(self::getRouteAsArray()) > 1)
                    {
                        // If the route if bigger than 1
                        $route = self::findBySection($routes);

                        if($route)
                        {
                            $this->routeToUse = $route;
                            self::useRoute($this->routeToUse);
                        }
                        elseif(!empty($this->default))
                        {
                            self::useRoute($this->default);
                        }
                    }
//                    else
//                    {
//                        if(isset($routes['routes']))
//                        {
//                            // If there are routes of size 1
//                            if(isset($routes['default']))
//                            {
//                                
//                            }
//                            
//                            foreach($routes['routes'] as $route)
//                            {
//                                if(trim($route['name'], '/') === (sizeof(self::getRouteAsArray()) > 0 ? self::getRouteAsArray()[0] : ''))
//                                {
//                                    $this->routeToUse = $route;
//                                    break;
//                                }
//                            }
//                            
//                            // If a route has been found
//                            if(!empty($this->routeToUse))
//                            {
//                                self::useRoute($this->routeToUse);
//                            }
//                            else
//                            {
//                                // Try to find a variable
//                                foreach($routes['routes'] as $route)
//                                {
//                                    if(self::is_var($route['name']))
//                                    {
//                                        $val = self::getRouteAsArray();
//                                        $val = $val[sizeof($val) - 1];
//                                        $this->vars[self::is_var($route['name'])[1]] = $val;
//                                        
//                                        $this->routeToUse = $route;
//                                        break;
//                                    }
//                                }
//                                
//                                // If a route with a var has been found
//                                if(!empty($this->routeToUse))
//                                {
//                                    self::useRoute($this->routeToUse);
//                                }
//                            }
//                        }
//                        else
//                        {
//                            die("No corresponding route found in $file." . PHP_EOL);
//                        }
//                    }
                }
            }
            else
            {
                die("The file $file does not exists." . PHP_EOL);
            }
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

    public function getRouteAsJSON()
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
    
    private function findBySection(array $routes, int $index = 0)
    {
        $currentRoute = self::getRouteAsArray();
        $route = [];
        $testVar = true;
        
        if(isset($routes['default']))
        {
            $this->default = $routes['default'];
        }
        
        if(sizeof($currentRoute) > 1)
        {
            $match = (sizeof($currentRoute) > ($index + 1)) ? 'sections' : 'routes';
            
            if(isset($routes[$match]))
            {
                foreach($routes[$match] as $section)
                {
                    if(isset($section['name']))
                    {
                        if($section['name'] == $currentRoute[$index])
                        {
                            $testVar = false;
                            if(sizeof($currentRoute) > $index + 1)
                            {
                                $route = self::findBySection($section, $index + 1);
                            }
                            else
                            {
                                $route = $section;
                            }
                            
                            break;
                        }
                    }
                }
                
                if($testVar)
                {
                    foreach($routes[$match] as $section)
                    {
                        if(isset($section['name']))
                        {
                            if(self::is_var($section['name']))
                            {
                                $this->vars[self::is_var($section['name'])[1]] = $currentRoute[$index];
                                
                                if(sizeof($currentRoute) > $index + 1)
                                {
                                    $route = self::findBySection($section, $index + 1);
                                }
                                else
                                {
                                    $route = $section;
                                }
                                
                                break;
                            }
                        }
                    }
                }
            }
        }
        else
        {
            if(isset($routes['routes']))
            {
                foreach($routes['routes'] as $section)
                {
                    if(isset($section['name']))
                    {
                        if(trim($section['name'], '/') == (isset($currentRoute[$index]) ? $currentRoute[$index] : ''))
                        {
                            $testVar = false;
                            $route = $section;
                            
                            break;
                        }
                    }
                }
                
                if($testVar)
                {
                    foreach($routes['routes'] as $section)
                    {
                        if(isset($section['name']))
                        {
                            if(self::is_var($section['name']))
                            {
                                $this->vars[self::is_var($section['name'])[1]] = $currentRoute[$index];
                                $route = $section;
                                
                                break;
                            }
                        }
                    }
                }
            }
        }
        
        return $route;
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
    
    private function add_controllers($controllers)
    {
        if($controllers != null)
        {
            if(is_array($controllers))
            {
                $this->controllers = array_merge($this->controllers, $controllers);
            }
            elseif(is_string($controllers))
            {
                $this->controllers[] = $controllers;
            }
        }
    }

    private function useRoute(array $route)
    {
        if(isset($route['controller']))
        {
            self::add_controllers($route['controller']);
        }
        
        // Invoke all controllers
        if(!empty($this->controllers))
        {
            $controller = new Controller($this->controllerDir);
            
            foreach($this->controlers as $c)
            {
                $controller->useController($c);
            }
        }
        
        // Update response
        if(isset($route['response']))
        {
            http_response_code(intval($route['response']));
        }
        
        // Render views
        if(isset($route['view']))
        {
            if($route['view'] != null)
            {
                $container = new Container();
                $container->getStorage()->mergeAttributes($this->vars);
                $view = new View($this->templateDir);
                $view->render($route['view']);
            }
        }
    }
}