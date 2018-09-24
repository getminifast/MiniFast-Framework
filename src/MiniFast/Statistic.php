<?php

namespace Minifast;

class Statistic
{
    private function getServerName()
    {
        return (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] === 'on' ? "https" : "http")
            . "://$_SERVER[HTTP_HOST]";
    }
    
    private function shouldLog()
    {
        // Set cache file name
        $cache = dirname(__FILE__) . '/.cache';
        
        // Read cache file
        $cacheFile = file_get_contents($cache, FILE_USE_INCLUDE_PATH);
        
        if ($cacheFile == base64_encode(date('Y/m/d'))) {
            return false;
        }
        
        return true;
    }
    
    private function log()
    {
        // Set cache file name
        $cache = dirname(__FILE__) . '/.cache';
        
        // Write current date in cache file
        file_put_contents(dirname(__FILE__) . '/' . $cache, base64_encode(date('Y/m/d')))
    }
}