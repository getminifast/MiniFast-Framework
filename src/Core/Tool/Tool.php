<?php

namespace MiniFast;

class Tool
{
    protected $needle;
    
    /**
     * Sets the variable to use
     * @param  mixed  $needle The variable to use
     * @return object This instance
     */
    public function select($needle)
    {
        $this->needle = $needle;
        
        return $this;
    }
    
    /* MOTHODS FOR STRINGS */
    
    /**
     * Decapitalize the first letter of a string
     * @param  boolean $upperRest True to uppercase the rest
     * @return string  The string edited
     */
    public function decapitalize($upperRest = false)
    {
        return lcfirst($upperRest ? strtoupper($string) : $string);
    }
    
    /**
     * Search the first string there is between the strings
     * from the parameter start and end
     * @param  string $start The string before
     * @param  string $end   The string after
     * @return string The string between $start and $end
     */
    public function firstStringBetween($start, $end)
    {
        return trim(strstr(strstr($this->needle, $start), $end, true), $start . $end);
    }
}
