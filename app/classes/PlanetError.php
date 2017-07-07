<?php

class PlanetError
{
    public $level;
    public $levels = array(
        1 => 'notice',
        2 => 'warning',
        3 => 'error',
    );
    public $message;

    /**
     * PlanetError constructor.
     * @param $level
     * @param $message
     */
    public function __construct($level, $message)
    {
        $this->level = (int) $level;
        $this->message = $message;
    }

    /**
     * @param  string $format
     * @return string
     */
    public function toString($format = '%1$s: %2$s')
    {
        return sprintf($format, $this->levels[$this->level], $this->message);
    }
}
