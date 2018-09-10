<?php

class Container extends Pimple
{
    public function __construct()
    {
        $this['user.storage.class'] = 'Storage';
        
        $this['user.storage'] = $this->share(function($c){
            return new $c['user.storage.class']();
        });
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
