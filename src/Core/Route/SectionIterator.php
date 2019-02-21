<?php

namespace MiniFast\Core\Route;

use \MiniFast\Core\IterableIterator as IT;

class SectionIterator extends IT
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
