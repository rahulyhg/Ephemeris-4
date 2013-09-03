<?php

namespace Ephemeris\Core\Models\Logs;

class Log extends \ArrayObject
{
    protected $logName;
    protected $logFile;
    
    protected $entries = array();
    
    // 
    
    protected function refresh()
    {
        $this->entries = array();
    }
    
    public function load($logName)
    {
        $this->logName = $logName;
        $this->logFile = "storage/{$logName}.csv";

        if (file_exists($this->logFile)) {
            $this->refresh();
            
            $handle = fopen($this->logFile, 'r+');
            while ($line = fgetcsv($handle)) {
                $this->addEntry($line);
            }
            fclose($handle);
            
            return true;
        } else {
            return false;
        }
    }
    
    public function save()
    {
        $tempFile = $this->logFile.uniqid().'.tmp';

        // 

        $handle = fopen($tempFile, 'a+');

        foreach ($this->getEntries() as $entry) {
            fputcsv($handle, $entry);
        }
        
        fclose($handle);
        
        // 
        
        rename($tempFile, $this->logFile);
    }
    
    public function addEntry(array $line)
    {
        $this->entries[] = $line;
    }
    
    public function getEntries()
    {
        return $this->entries;
    }
   
    /* */
    
}