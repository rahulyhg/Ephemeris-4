<?php

namespace Ephemeris\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Display extends \Ephemeris\CommandScaffolding
{
    protected function configure()
    {
        $this
            ->setName('display')
            ->setDescription('Show logs for a given date')
            ->setAliases(
                array('show')
            )
            ->addArgument(
                'date',
                InputArgument::OPTIONAL,
                'Specify a date to lookup'
            )
            ->addOption(
               'expected',
               'e',
               InputOption::VALUE_REQUIRED,
               '(Optional) Total hours in the day to compare against.'
            )
            ->addOption(
               'grouped',
               'g',
               InputOption::VALUE_NONE,
               '(Optional) Group by tag.'
            )
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $date = $input->getArgument('date');
        
        // 
        
        $dateTransformer = new \Ephemeris\Components\Date\Transformer;
        
        try {
            $datetime = $dateTransformer->transform($date);

            // 

            $log = new \Ephemeris\Core\Models\Logs\Log;
            
            if (!$log->load($datetime->getTimestamp())) {
                $output->writeln("\nNothing to report.");
            } else {
                $expected = $input->getOption('expected');
                
                $expected = $expected
                                ? round($expected * 60, 2)
                                : 0;
                
                if ($input->getOption('grouped')) {
                    $this->displayGrouped(
                        $input,
                        $output,
                        $log,
                        $expected
                    );
                } else {
                    $this->displayNormal(
                        $input,
                        $output,
                        $log,
                        $expected
                    );
                }
            }
        } catch (\Exception $exception) {
            $output->writeln("Unknown error: {$exception->getMessage()}");
        }
    }
    
    //
    
    protected function displayGrouped($input, $output, $log, $expectedMinutes) {
        if ($expectedMinutes) {
            $this->setHeaders('Group', 'Hours', '%');
        } else {
            $this->setHeaders('Group', 'Hours');
        }
        
        $groups = array();

        foreach ($log->getEntries() as $entry) {
            $tags = json_decode($entry[3], true);
            
            if (empty($tags)) {
                $tags = array('(Unspecified)');
            }
            
            foreach ($tags as $tag) {
                $tag = trim($tag);
                
                if ($tag) {
                    if(!isset($groups[$tag])) {
                        $groups[$tag] = 0;
                    }

                    $groups[$tag] += (real)$entry[1];
                }
            }
        }
        
        //
        
        $totalMinutes = 0;
        
        foreach ($groups as $group => $minutes) {
            $hours = round($minutes / 60, 2);
            
            $totalMinutes += $minutes;
            
            $row = array(
                $group,
                $hours
            );
            
            if ($expectedMinutes) {
                $row[] = round(($minutes / $expectedMinutes) * 100, 1);
            }

            $this->setRow($row);
        }
        
        // Output table.

        $this->writeTable($output);
        
        // Show totals.
        
        $totalHours = round($totalMinutes / 60, 2);
        
        $message = 'Total hours: '.number_format($totalHours, 2);
        
        if ($expectedMinutes) {
            $expectedPercentage = round(($totalMinutes / $expectedMinutes) * 100, 1);
            
            $message .= " ({$expectedPercentage}%)";
        }

        $output->writeln("\n    {$message}");
    }
    
    protected function displayNormal($input, $output, $log, $expectedMinutes) {
        if ($expectedMinutes) {
            $this->setHeaders('Task', 'Hours', '%', 'Tags', 'Logged');
        } else {
            $this->setHeaders('Task', 'Hours', 'Tags', 'Logged');
        }
        
        $totalMinutes = 0;

        foreach ($log->getEntries() as $entry) {
            $minutes = (real)$entry[1];
            $hours = round($minutes / 60, 2);
            
            $totalMinutes += $minutes;

            $row = array(
                $entry[0],
                number_format($hours, 2),
            );

            if ($expectedMinutes) {
                $row[] = round(($minutes / $expectedMinutes) * 100, 1);
            }

            $tags = json_decode($entry[3], true);

            $row[] = implode(", ", $tags);

            $row[] = date('H:i:s', $entry[2]);

            $this->setRow($row);
        }

        // Output table.

        $this->writeTable($output);

        // Show totals.
        
        $totalHours = round($totalMinutes / 60, 2);
        
        $message = 'Total hours: '.number_format($totalHours, 2);
        
        if ($expectedMinutes) {
            $expectedPercentage = round(($totalMinutes / $expectedMinutes) * 100, 1);
            
            $message .= " ({$expectedPercentage}%)";
        }

        $output->writeln("\n    {$message}");
    }
}