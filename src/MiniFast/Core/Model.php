<?php

namespace MiniFast\Core;

class Controller
{
    /**
     * Execute a controller
     * @param string $controller The controller name
     */
    public function useController(string $controller)
    {
        $c = $this->getFullClassName($controller);
        $c = new $c();
        $c->index();
    }

    /**
     * Determine the full-qualified class name
     * @param  string $class_name The class name
     * @return string The fully qualified class name
     */
    protected function getFullClassName(string $class_name)
    {
        $relative_class = str_replace('.', '\\', $class_name);

        $qualified_class = 'MiniFast\Controller\\'
            . $relative_class;

        return $qualified_class;
    }
}
