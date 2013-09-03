<?php

date_default_timezone_set('Europe/London');

include 'vendor/autoload.php';

\Ephemeris\Config::load('config/config.yml');

// 

$application = new \Symfony\Component\Console\Application();

$application->add(
    new \Ephemeris\Commands\Display
);

$application->add(
    new \Ephemeris\Commands\Log
);

$application->run();