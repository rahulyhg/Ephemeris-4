<?php

namespace Ephemeris;

class Config
{
    protected static $configFile;
    protected static $config = array();
    
    public static function load($configFile)
    {
        self::$configFile = $configFile;
        
        $yaml = new \Symfony\Component\Yaml\Parser();
        self::$config = $yaml->parse(file_get_contents($configFile));
    }

    public static function get($field)
    {
        return self::$config[$field];
    }

    public static function set($field, $value)
    {
        self::$config[$field] = $value;
    }

    public static function save($configFile = null)
    {
        if (is_null($configFile)) {
            $configFile = self::$configFile;
        }
        
        // 

        $dumper = new \Symfony\Component\Yaml\Dumper();
        $yaml = $dumper->dump(self::$config);

        file_put_contents($configFile, $yaml);
    }
    
    //
    
    protected static function walk()
    {
        
    }
}