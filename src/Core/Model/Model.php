<?php

namespace MiniFast\Core;

class Controller
{
    /**
     * Execute a controller
     * @param string $model The controller name
     */
    public function useModel(string $model)
    {
        $class = $this->getFullClassName($model);
        $class = new $class();
        $class->index();
    }

    /**
     * Determine the full-qualified class name
     * @param  string $class_name The class name
     * @return string The fully qualified class name
     */
    protected function getFullClassName(string $class_name)
    {
        $relative_class = str_replace('.', '\\', $class_name);

        $qualified_class = 'MiniFast\Model\\'. $relative_class;

        return $qualified_class;
    }
}
