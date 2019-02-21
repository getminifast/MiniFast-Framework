<?php

namespace MiniFast\Core;

class IterableIterator implements \SeekableIterator, \ArrayAccess, \Countable
{
    private $elems = [];
    private $key = 0;

    /**
     * Add an elem
     * @param mixed $elem The element to add
     */
    public function add($elem)
    {
        $this->elems[] = $elem;
    }

    /**
     * Return the current element
     * @return mixed The current element
     */
    public function current()
    {
        return $this->elems[$this->key];
    }

    /**
     * Return the current position
     * @return int The current position
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Move the cursor to the next element
     */
    public function next()
    {
        $this->key++;
    }

    /**
     * Move the cursor at 0
     */
    public function rewind()
    {
        $this->key = 0;
    }

    /**
     * Move the cursor at a defined position
     * @param int $position The position to move at
     */
    public function seek($position)
    {
        $oldPosition = $this->key;
        $this->key = $position;

        if (!$this->valid()) {
            \trigger_error('The specified position is not valid', E_USER_WARNING);
            $this->key = $oldPosition;
        }
    }

    /**
     * Test if the current element exists
     * @return bool True if exists
     */
    public function valid()
    {
        return isset($this->elems[$this->key]);
    }

    /**
     * Test if the key exists
     * @param int $key The key
     */
    public function offsetExists($key)
    {
        return isset($this->elems[$key]);
    }

    /**
     * Return the element at $key
     * @param int $key The key
     */
    public function offsetGet($key)
    {
        return $this->elems[$key];
    }

    /**
     * Set $value at $key offset
     * @param int   $key   The key
     * @param mixed $value The value to set
     */
    public function offsetSet($key, $value)
    {
        $this->elems[$key] = $value;
    }

    /**
     * Unset offset at $key
     * @param int $key The key
     */
    public function offsetUnset($key)
    {
        unset($this->elems[$key]);
    }

    /**
     * Return the number of elements
     * @return int The number of elements
     */
    public function count()
    {
        return count($this->elems);
    }
}
