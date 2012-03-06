<?php

/**
 * Planet person
 */
class PlanetFeed extends SimplePie{
    var $name;
    var $feed;
    var $website;

    function __construct($name, $feed, $website){
        $this->name = $name;
        $this->feed = $feed;
        $this->website = $website;
        parent::__construct();
        $this->set_item_class('PlanetItem');
        $this->set_cache_location(dirname(__FILE__).'/../../cache');
        $this->set_autodiscovery_level(SIMPLEPIE_LOCATOR_NONE);
        $this->set_feed_url($this->getFeed());
        $this->set_timeout(5);
        $this->set_stupidly_fast(true);
    }

    function getFeed(){
        return $this->feed;
    }

    function getName(){
        return $this->name;
    }

    function getWebsite(){
        return $this->website;
    }

    function compare($person1, $person2){
        return strcasecmp($person1->name, $person2->name);
    }
}