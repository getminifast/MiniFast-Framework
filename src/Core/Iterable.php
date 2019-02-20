<?php

namespace MiniFast\Core\Route;

class SectionIterator implements SeekableIterator
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
    public function seek(int $position)
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
}
