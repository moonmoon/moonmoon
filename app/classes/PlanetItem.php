<?php

/**
 * Planet item
 */

class PlanetItem
{
    private $guid;
    private $permalink;
    private $date;
    private $title;
    private $author;
    private $content;
    private $feedUrl;

    public function __construct($feed, $data)
    {
        parent::SimplePie_Item($feed, $data);
        $this->guid = "plop";
        $this->feedUrl = $this->get_feed()->feed_url;
    }

    function __get($name)
    {
        error_log("get $name");
        if (property_exists('PlanetItem', $name))
        {
            return $this->$name;
        }
    }

    public function compare($item1, $item2)
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
