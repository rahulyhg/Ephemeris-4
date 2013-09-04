<?php

namespace Ephemeris;

class Core
{
    public static function startup($baseDirectory)
    {
        // Work from base directory.
        
        chdir($baseDirectory);
        
        // Load config.

        Config::load('config/config.yml');

        // Configure timezone.
        
        $timezone = Config::get('timezone');

        if ($timezone) {
            if (!date_default_timezone_set($timezone)) {
                throw new \RuntimeException('Invalid timezone.');
            }
        }
    }
}