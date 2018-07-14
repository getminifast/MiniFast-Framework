<?php

class Controller
{
    private $controllers = [];
    private $twig;

    public function __construct(string $directory = '')
    {
        if(!empty($directory))
        {
            self::parseControllers($directory);
        }
    }

    public function parseControllers(string $directory, string $lastDir = '')
    {
        $directory = '/' . trim($directory, '/');
        $all = scandir('/' . $directory);

        foreach($all as $file)
        {
            // Excluding . and ..
            if($file != '.' and $file != '..')
            {
                // If $file is a dir, we scan the dir (recursive)
                if(is_dir($directory . '/' . $file))
                {
                    self::parseControllers($directory . '/' . $file, $lastDir . ucfirst(strtolower($file)));
                }
                else
                {
                    // 'Controller.php' at the end of the file name indicate it's a controller
                    if(preg_match('/Controller\.php$/', $file))
                    {
                        $controllerName = ucfirst(strtolower(str_replace('Controller.php', '', $file)));
                        if(strlen($controllerName) > 0)
                        {
                            $this->controllersListed[] = $file;
                            $this->controllers[$lastDir . $controllerName] = [
                                'name' => $lastDir . $controllerName,
                                'path' => $directory,
                                'fullPath' => $directory . '/' . $file
                            ];
                        }
                    }
                }
            }
        }
    }

    public function listControllers()
    {
        return $this->controllers;
    }

    public function useController(string $controller, array $vars = [])
    {
        $_SESSION['route'] = $vars;

        if(array_key_exists($controller, $this->controllers))
        {
            include($this->controllers[$controller]['fullPath']);
        }
        else
        {
            die("The controller `$controller` does not exist."); 
        }
    }
}