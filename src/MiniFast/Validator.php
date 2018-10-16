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
     * Check if needle is boolean
     * @return boolean True if boolean
     */
    public function isBool()
    {
        return gettype($this->check) === 'boolean';
    }

    /**
     * Check if needle is integer
     * @return boolean True if integer
     */
    public function isInt()
    {
        return gettype($this->check) === 'integer';
    }

    /**
     * Check if needle is float
     * @return boolean True if float
     */
    public function isFloat()
    {
        return gettype($this->check) === 'double';
    }

    /**
     * Check if needle is string
     * @return boolean True if string
     */
    public function isString()
    {
        return gettype($this->check) === 'string';
    }

    /**
     * Check if needle is array
     * @return boolean True if array
     */
    public function isArray()
    {
        return gettype($this->check) === 'array';
    }

    /**
     * Check if needle is object
     * @return boolean True if object
     */
    public function isObject()
    {
        return gettype($this->check) === 'object';
    }

    /**
     * Check if needle is resource
     * @return boolean True if resource
     */
    public function isResource()
    {
        return gettype($this->check) === 'resource';
    }

    /**
     * Check if needle is null
     * @return boolean True if null
     */
    public function isNull()
    {
        return gettype($this->check) === 'null';
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
