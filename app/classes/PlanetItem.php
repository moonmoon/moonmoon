<?php

/**
 * Planet item
 */
class PlanetItem{
    function __construct($feed, $data){
        parent::SimplePie_Item($feed, $data);
    }

    function compare($item1, $item2){
        $item1_date = $item1->get_date('U');
        $item2_date = $item2->get_date('U');
        if ($item1_date == $item2_date){
            return 0;
        }
        else if ($item1_date < $item2_date){
            return 1;
        }
        return -1;
    }
}