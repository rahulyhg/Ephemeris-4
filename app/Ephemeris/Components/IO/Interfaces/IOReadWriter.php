<?php

namespace Ephemeris\Components\IO\Interfaces;

interface IOReadWriter
{
    public function read();
    public function write(array $data);
}