<?php

namespace MiniFast;

class Validator
{
    protected $check;
    
    /**
     * Set the variable to validate
     * @param  mixed  $needle The variable we want to test
     * @return object This so we can call other method on the same line
     */
    public function validate($needle)
    {
        $this->check = $needle;
        
        return $this;
    }
    
    /**
     * Test the lenght of a string/array
     * @param int     $min The lenght to test or the min if max is defined
     * @param int     $max The max length
     * @return boolean True if it matches the lenght
     */
    public function isLen(int $min, int $max = 0)
    {
        if (is_array($this->check)) {
            if (sizeof($this->check) === $min) {
                return true;
            }
        }
        
        if (is_string($this->check)) {
            if ($max !== 0) {
                if (strlen($this->check) >= $min and strlen($this->check) <= abs($max)) {
                    return true;
                }
            } else {
                if (strlen($this->check) === $min) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Test if string is a hex
     * @return boolean True if hex
     */
    public function isHex()
    {
        if (is_string($this->check)) {
            return preg_match('/^[0-9a-f]++$/i', $str);
        }
        
        if (is_numeric($this->check)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Shortcut for filter_var, test if string is an email
     * @return boolean True if string is an email
     */
    public function isEmail()
    {
        if (is_string($this->check)) {
            return filter_var($this->check, FILTER_VALIDATE_EMAIL);
        }
        
        return false;
    }
    
    /**
     * Shortcut for is_bool
     * @return boolean True if value is a boolean
     */
    public function isBool()
    {
        return is_bool($this->check);
    }
}