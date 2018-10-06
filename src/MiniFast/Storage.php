<?php

namespace MiniFast;

class Storage extends Singleton
{
    protected $storage = [];
    
    /**
     * Shortcut of getAttribute()
     * @param  mixed $key The key of the array
     * @return mixed The value of the key
     */
    public function __get($key)
    {
        if (!$this->isset($key)) {
            throw new Exception("The variable $key is not defined");
        } else {
            return $this->getAttribute($key);
        }
    }
    
    /**
     * Shortcut of setAttribute()
     * @param mixed $key   The key of the array
     * @param mixed $value The value of the key
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Store an attribute
     * @param mixed $key   The key of the array
     * @param mixed $value The value of the key
     */
    public function setAttribute($key, $value)
    {
        $this->storage[$key] = $value;
    }

    /**
     * Get the asked attribute
     * @param  mixed $key The key of the array
     * @return mixed The value of the key
     */
    public function getAttribute($key)
    {
        return $this->storage[$key];
    }

    /**
     * Store one or more attributes by merging them
     * @param array $array The array to merge
     */
    public function setAttributes(array $array)
    {
        array_merge($this->storage, $array);
    }

    /**
     * Get all attributes
     * @return array All attributes
     */
    public function getAttributes()
    {
        return (array) $this->storage;
    }

    /**
     * Test the existence of a key
     * @param  mixed   $key The key to find
     * @return boolean [[Description]]
     */
    public function isset($key)
    {
        return isset($this->storage[$key]);
    }
}