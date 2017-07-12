<?php

/**
 * Planet person
 */

class PlanetFeed extends SimplePie
{
    public $name;
    public $feed;
    public $website;
    public $isDown;

    public function __construct($name, $feed, $website, $isDown)
    {
        $this->name    = $name;
        $this->feed    = $feed;
        $this->website = $website;
        $this->isDown  = $isDown;
        parent::__construct();
        $this->set_item_class('PlanetItem');
        $this->set_cache_location(__DIR__.'/../../cache');
        $this->set_autodiscovery_level(SIMPLEPIE_LOCATOR_NONE);
        $this->set_feed_url($this->getFeed());
        $this->set_timeout(5);
        $this->set_stupidly_fast(true);
    }

    public function getFeed()
    {
        return $this->feed;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function getIsDown()
    {
        return $this->isDown;
    }

    /**
     * Compare two Person by their name.
     *
     * @param  $person1
     * @param  $person2
     * @return int
     */
    public static function compare($person1, $person2)
    {
        return strcasecmp($person1->name, $person2->name);
    }
}
