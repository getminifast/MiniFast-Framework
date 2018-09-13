<?php

namespace MiniFast;

class Controller
{
    /**
     * Create new instance of *Controller*
     */
    public function __construct()
    {
        $loader = new Autoloader();
        $loader->addNamespace('MiniFast\Controller', $directory);
    }
    
    /**
     * Execute a controller
     * @param string $controller The controller name
     */
    public function useController(string $controller)
    {
        $c = new $this->getFullClassName($controller)();
        $c->index();
    }
    
    /**
     * Determine the full-qualified class name
     * @param  string $class_name The class name
     * @return string The fully qualified class name
     */
    protected function getFullClassName(string $class_name)
    {
        $relative_class = trim(str_replace('.', '\\', $controller), '\\')
            . '.php';
        
        $qualified_class = 'MiniFast\Controller\\'
            . $relative_class;
        
        return $qualified_class;
    }
}
