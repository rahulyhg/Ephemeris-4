<?php

namespace Ephemeris\Components\Date;

class Transformer
{
    public function transform($date)
    {
        $date = explode('/', $date);

        if (empty($date[0])) {
            $date[0] = date('d');
        }
        
        if (empty($date[1])) {
            $date[1] = date('m');
        }
        
        if (empty($date[2])) {
            $date[2] = date('y');
        }

        $date = implode('/', $date);
        
        $time = \DateTime::createFromFormat('d/m/y H:i:s', "{$date} 00:00:00");
        
        return $time;
    }
}