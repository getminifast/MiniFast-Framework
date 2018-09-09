<?php

class Storage
{
    private $storage = [];
    
    public function setAttribute($key, $value)
    {
        $this->storage[$key] = $value;
    }
    
    public function getAttribute($key)
    {
        return $this->storage[$key];
    }
    
    public function mergeAttributes(array $attributes)
    {
        $this->storage = array_merge($this->storage, $attributes);
    }
    
    public function getAll()
    {
        return $this->storage;
    }
}