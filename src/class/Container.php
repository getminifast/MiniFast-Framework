<?php

class Container
{
    private $parameters = [
        'storage.class' => 'Storage'
    ];

    public function __construct(array $parameters = [])
    {
        $this->parameters = $this->addParameter($parameters);
    }
    
    public function addParameter(array $parameters)
    {
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    public function getStorage()
    {
        static $instance;
        if (!isset($instance))
        {
            $instance = new Storage();
        }

        return $instance;
    }
}
