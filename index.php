<?php

include 'vendor/autoload.php';

try {
    \Ephemeris\Core::startup(__DIR__);

    // 

    $application = new \Symfony\Component\Console\Application;

    $application->add(
        new \Ephemeris\Commands\Display
    );

    $application->add(
        new \Ephemeris\Commands\Log
    );

    $application->run();
} catch (\Exception $exception) {
    $output = new \Symfony\Component\Console\Output\ConsoleOutput;

    $output->writeln("\nConfig error: {$exception->getMessage()}");
}