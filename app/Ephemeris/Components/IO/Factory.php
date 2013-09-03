<?php

namespace Ephemeris\Components\IO;

class Factory
{
    public static function create($driver = null)
    {
        if (!$driver) {
            $driver = \Ephemeris\Config::get('io_driver');
        }
        
        // 
        
        $driver = strtoupper($driver);
        
        $className = '\\Ephemeris\\Components\\IO\\ReadWriters\\'.$driver.'ReadWriter';
        
        if (class_exists($className)) {
            return new $className;
        }
    }
}