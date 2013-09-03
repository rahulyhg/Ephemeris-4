<?php

namespace Ephemeris\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Log extends \Ephemeris\CommandScaffolding
{
    protected function configure()
    {
        $this
            ->setName('log')
            ->setDescription('Log time for a task')
            ->addArgument(
                'task',
                InputArgument::OPTIONAL,
                'Name of the task being logged'
            )
            ->addOption(
               'minutes',
               'm',
               InputOption::VALUE_REQUIRED,
               '(Optional) Number of minutes spent on task.'
            )
            ->addOption(
               'hours',
               null,
               InputOption::VALUE_REQUIRED,
               '(Optional) Number of hours spent on task.'
            )
            ->addOption(
               'tag',
               null,
               InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
               '(Optional) Tags to "categorise" task.'
            )
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $task = $input->getArgument('task');
        
        if (!$task) {
            $task = '(Unspecified)';
        }
        
        $tags = $input->getOption('tag');

        // 
        
        $timestamp = strtotime(date('Y-m-d'));
        
        $log = new \Ephemeris\Core\Models\Logs\Log;
        $log->load($timestamp);
        
        // 
        
        $minutes = round($input->getOption('minutes'), 2);
        $hours = round($input->getOption('hours'), 2);
        
        if ($hours) {
            $minutes += round(round($hours * 60), 2);
        }

        // 
        
        $log->addEntry(
            array(
                $task,
                $minutes,
                time(),
                json_encode($tags)
            )
        );
        
        $log->save();

        // 
        
        $output->writeln("\nLogged {$minutes} minute(s) on \"{$task}.\"");
    }
}