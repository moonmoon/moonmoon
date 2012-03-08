<?php 

class PlanetError {
    var $level;
    var $message;

    function __construct($level, $message) {
        $this->level = (int) $level;
        $this->message = $message;
    }

    function toString($format = '%1$s : %2$s') {
        $levels = array(
            1 => "notice",
            2 => "warning",
            3 => "error"
        );
        return sprintf($format, $levels[$this->level], $this->message);
    }
}