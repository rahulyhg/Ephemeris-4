<?php

namespace Ephemeris;

class CommandScaffolding extends \Symfony\Component\Console\Command\Command
{
    protected $tableHeader;
    protected $tableRows = array();
    
    public function setHeaders($header = null)
    {
        if (is_array($header)) {
            $this->tableHeader = $header;
        } else {
            $this->tableHeader = func_get_args();
        }
        
        return $this;
    }
    
    public function setRow(array $row)
    {
        $this->tableRows[] = $row;
        
        return $this;
    }
    
    public function writeTable(\Symfony\Component\Console\Output\OutputInterface $output)
    {
        $table = $this->getApplication()->getHelperSet()->get('table');
        $table
            ->setHeaders($this->tableHeader)
            ->setRows($this->tableRows)
        ;
        
        $table->render($output);
        
        //

        $this->tableHeader = null;
        $this->tableRows = array();
    }
}