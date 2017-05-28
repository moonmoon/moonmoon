<?php

/**
 * Planet item
 */

class PlanetItem extends SimplePie_Item
{
    public function __construct($feed, $data)
    {
        parent::__construct($feed, $data);
    }

    /**
     * @param PlanetItem $item1
     * @param PlanetItem $item2
     * @return int
     */
    public static function compare($item1, $item2)
    {
        $item1_date = $item1->get_date('U');
        $item2_date = $item2->get_date('U');

        if ($item1_date == $item2_date) {
            return 0;
        } elseif ($item1_date < $item2_date) {
            return 1;
        }

        return -1;
    }
}
