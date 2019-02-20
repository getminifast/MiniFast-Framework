<?php

namespace MiniFast\Core\Route;

use \MiniFast\Core\Iterable;

class SectionIterator extends Iterable
{
    /**
     * Add a section
     * @param Section $section The section to add
     */
    public function add(Section $section)
    {
        $this->elems[] = $section;
    }
}
