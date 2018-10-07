<?php
namespace MiniFast;
class Route
{
    private $route;
    private $routeToUse;
    private $default = [];
    private $vars = [];
    private $controllerDir;
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

    /**
     * Select a file and search for the corresponding route
     * @param string $file        The file to open
     * @param string $templateDir The template directory
     */
    public function fromFile($file, string $templateDir = '')
    {

        if (!empty($templateDir)) {
            $this->templateDir = $templateDir;
        }

        // If there are multiple routing files, check all files
        if (is_array($file)) {
            foreach ($file as $f) {
                if (is_string($f)) {
                    // Does the file exists?
                    if (file_exists($f)) {
                        $this->fromFile($f);
                    }
                }
            }
        } elseif (is_string($file)) {
            // Does the file exists?
            if (file_exists($file)) {
                $routes = json_decode(file_get_contents($file), true);
                if ($routes === null) {
                    die("$file is not a valid JSON." . PHP_EOL);
                } else {
                    // If all seems ok, start parsing
                    // If the route if bigger than 1
                    $route = $this->findBySection($routes);
                    if ($route) {
                        $this->routeToUse = $route;
                        $this->useRoute($this->routeToUse);
                    } elseif (!empty($this->default)) {
                        $this->useRoute($this->default);
                    }
                }
            }
            else
            {
                die("The file $file does not exists." . PHP_EOL);
            }
        }
    }
    
    /**
     * Get the route
     * @return array The route as an array
     */
    public function getRoute()
    {
        return $this->route;
    }
    
    /**
     * Get the route from URI
     * @return array The route 
     */
    public function getRouteAsArray()
    {
        $route = trim($this->route, '/');
        $routes = explode('/', $route);
        $cleanRoute = [];
        foreach ($routes as $route) {
            if (trim($route) != '') {
                $cleanRoute[] = $route;
            }
        }
        return $cleanRoute;
    }
    
    /**
     * Get the route in JSON format
     * @return string The JSON string
     */
    public function getRouteAsJSON()
    {
        return json_encode($this->getRouteAsArray());
    }

    /**
     * Search in a section if there is the route we want
     * @param  array $routes The route to search into
     * @param int   $index  The current index of the route
     * @return array The route we wanted, thank you
     */
    private function findBySection(array $routes, int $index = 0)
    {
        $currentRoute = $this->getRouteAsArray();
        $route = [];
        $testVar = true;

        if (isset($routes['default'])) {
            $this->mergeDefault($routes['default']);
        }

        if (sizeof($currentRoute) > 1) {
            $match = (sizeof($currentRoute) > ($index + 1)) ? 'sections' : 'routes';

            if (isset($routes[$match])) {
                foreach ($routes[$match] as $section) {
                    if (isset($section['name'])) {
                        if ($section['name'] == $currentRoute[$index]) {
                            $testVar = false;

                            if (sizeof($currentRoute) > $index + 1) {
                                $route = $this->findBySection($section, $index + 1);
                            } else {
                                $route = $section;
                            }

                            break;
                        }
                    }
                }

                if ($testVar) {
                    foreach ($routes[$match] as $section) {
                        if (isset($section['name'])) {
                            if ($this->isVar($section['name'])) {
                                $this->vars[$this->getVar($section['name'])] = $currentRoute[$index];

                                if (sizeof($currentRoute) > $index + 1) {
                                    $route = $this->findBySection($section, $index + 1);
                                } else {
                                    $route = $section;
                                }

                                break;
                            }
                        }
                    }
                }
            }
        } else {
            if (isset($routes['routes'])) {
                foreach ($routes['routes'] as $section) {
                    if (isset($section['name'])) {
                        if (trim($section['name'], '/') == (isset($currentRoute[$index]) ? $currentRoute[$index] : '')) {
                            $testVar = false;
                            $route = $section;

                            break;
                        }
                    }
                }

                if ($testVar) {
                    foreach ($routes['routes'] as $section) {
                        if (isset($section['name'])) {
                            if ($this->isVar($section['name'])) {
                                $this->vars[$this->getVar($section['name'])] = $currentRoute[$index];
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

    /**
     * Merge new default settings with old one
     * @param array $default The new defaults
     */
    private function mergeDefault(array $default)
    {
        $this->default = array_merge($this->default, $default);
    }
    
    /**
     * Test if $key is a route variable.
     * @param string  $key The key to found.
     * @return boolean True if it is a route variable, else false.
     */
    private function isVar(string $key)
    {
        if(substr($key, 0, 1) === '{' and substr($key, -1) === '}')
        {
            return true;
        }

        return false;
    }

    /**
     * Remove first and last character.
     * @param  string $var The variable.
     * @return string The variable without its first and last character.
     */
    protected function getVar($var)
    {
        if ($this->isVar($var)) {
            return substr(substr($var, 0, -1), 1);
        }

        return null;
    }

    /**
     * Add controllers in controllers array
     * @param mixed $controllers One ore more controllers names
     */
    private function addController($controllers)
    {
        if ($controllers != null) {
            if (is_array($controllers)) {
                $this->controllers = array_merge($this->controllers, $controllers);
            } elseif (is_string($controllers)) {
                $this->controllers[] = $controllers;
            }
        }
    }
    
    /**
     * Use the route specified
     * @param array $route The route found in routing file given by the user
     */
    private function useRoute(array $route)
    {
        // New container
        $container = new Container();
        
        // Insert vars in Storage
        $container
            ->getStorage()
            ->setAttributes($this->vars);
        
        if (isset($route['controller']) and $route['controller'] !== null) {
            $this->addController($route['controller']);
        }

        // Invoke all controllers
        if (!empty($this->controllers)) {
            $controller = new Controller($this->controllerDir);

            foreach ($this->controllers as $c) {
                $controller->useController($c);
            }
        }

        // Redirect
        if (isset($route['redirect'])) {
            if (is_string($route['redirect'])) {
                header('Location: /' . trim($route['redirect'], '/'));
                exit;
            }
        }

        // Update response
        if (isset($route['response'])) {
            http_response_code(intval($route['response']));
        }

        // Render view
        if (isset($route['view'])) {
            if ($route['view'] != null) {
                // Render the view
                $view = $container->getView($this->templateDir);
                $view->render($route['view']);
            }
        }
    }
}