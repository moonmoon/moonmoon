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
    private $feed;

    public function __construct($data = null)
    {
        if ($data) {
            $default = array(
                'guid' => '',
                'permalink' => '',
                'date' => '',
                'title' => '',
                'author' => '',
                'content' => '',
                'feedUrl' => '',
                'feed' => null
            );
            foreach (array_keys($default) as $attr) {
                if (array_key_exists($attr, $data)) {
                    $this->{$attr} = $data[$attr];
                } else {
                    $this->{$attr} = $default[$attr];
                }
            }
        }
    }

    public function initFromSimplepieItem($Simplepie_Item, $feed) {
        if ("SimplePie_Item" !== get_class($Simplepie_Item)) {
            return;
        }
        $this->guid = $Simplepie_Item->get_id();
        $this->permalink = $Simplepie_Item->get_permalink();
        $this->date = $Simplepie_Item->get_date() ? $Simplepie_Item->get_date('U') : date('U');
        $this->title = $Simplepie_Item->get_title();
        $this->author = $Simplepie_Item->get_author()? $Simplepie_Item->get_author()->get_name() : '';
        $this->content = $Simplepie_Item->get_content();
        $this->feedUrl = $Simplepie_Item->get_feed()->feed_url;    

        $this->feed = $feed;
    }

    public function __get($name)
    {
        if (property_exists('PlanetItem', $name))
        {
            return $this->$name;
        }
    }

    public function __isset($name)
    {
        if (property_exists('PlanetItem', $name)) {
            return true;
        }
        return false;
    }

    public function get_id() {
        return $this->id;
    }

    public function get_date($format='c') {
        return date($format, $this->date);
    }

    public function get_feed() {
        return $this->feed;
    }

    public function set_feed($feed) {
        $this->feed = $feed;
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
