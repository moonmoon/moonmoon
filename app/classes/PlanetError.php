<?php

class PlanetError
{
    public $level;
    public $message;

    public function __construct($level, $message)
    {
        $this->level = (int) $level;
        $this->message = $message;
    }

    public function toString($format = '%1$s : %2$s')
    {
        $levels = array(
            1 => 'notice',
            2 => 'warning',
            3 => 'error',
        );
        return sprintf($format, $levels[$this->level], $this->message);
    }
}
