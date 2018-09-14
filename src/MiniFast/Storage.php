<?php

namespace MiniFast;

class Storage extends MiniFast\Singleton
{
    protected $storage = [];

    /**
     * Store an attribute
     * @param mixed $key   The key of the array
     * @param mixed $value The value relative to the key
     */
    public function setAttribute($key, $value)
    {
        $this->storage[$key] = $value;
    }

    /**
     * Get the asked attribute
     * @param  mixed $key The key of the array
     * @return mixed The value relative to the key
     */
    public function getAttribute($key)
    {
        if (isset($this->storage[$key])) {
            return $this->storage[$key];
        }

        return null;
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
        return $this->storage;
    }

    /**
     * Test the existence of a key
     * @param  mixed   $key The key to find
     * @return boolean [[Description]]
     */
    public function isset($key)
    {
        return \isset($this->storage[$key]);
    }
}