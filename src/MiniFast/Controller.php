<?php

namespace MiniFast;

class Controller
{
    private $directory;
    
    /**
     * Create new instance of *Controller*
     * @param string $directory This is the directory where controllers are stored
     */
    public function __construct(string $directory)
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
        try
        {
            $c = new $this->getFullClassName($controller)();
            $c->index();
        }
        catch(Exception $e)
        {
            
        }
    }
    
    /**
     * Determine the full-qualified class name
     * @param  string $class_name The class name
     * @return string The fully qualified class name
     */
    protected function getFullClassName(string $class_name)
    {
        $relative_class = trim(str_replace('.', '\\', $controller), '\\')
            . 'Controller.php';
        $qualified_class = 'MiniFast\Controller\\'
            . $relative_class;
        
        return $qualified_class;
    }
}