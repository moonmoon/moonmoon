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
        if (is_array($data)) {
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
        } else if (is_a($data, "SimplePie_Item")) {
            $this->guid      = $data->get_id();
            $this->permalink = $data->get_permalink();
            $this->date      = $data->get_date() ? $data->get_date('U') : date('U');
            $this->title     = $data->get_title();
            $this->author    = $data->get_author()? $data->get_author()->get_name() : '';
            $this->content   = $data->get_content();
            $this->feedUrl   = $data->get_feed()->feed_url;    

            $this->feed = $data->get_feed();
        }
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
