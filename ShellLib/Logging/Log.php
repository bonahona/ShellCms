<?php

// Base class for implementation of logging files
class Log
{
    public function Setup($config){}
    public function Write($data, $logLevel = LOGGING_NOTICE){}
}