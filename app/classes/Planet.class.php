<?php
/*
Copyright (c) 2006, Maurice Svay
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are
met:

* Redistributions of source code must retain the above copyright notice,
this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright
notice, this list of conditions and the following disclaimer in the
documentation and/or other materials provided with the distribution.
* Neither the name of Maurice Svay nor the names of its
contributors may be used to endorse or promote products derived from
this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS
IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A
PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER
OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

/**
 * Planet, main app class
 */
class Planet
{
    public $config;
    public $items;
    public $people;
    public $errors;

    public function __construct($config=null)
    {

        if ($config == null) {
            $this->config = new PlanetConfig(array());
        } else {
            $this->config = $config;
        }

        $this->items  = array();
        $this->people = array();
        $this->errors = array();
    }

    /**
     * Getters
     */
    public function getItems()
    {
        return $this->items;
    }

    public function getPeople()
    {
        return $this->people;
    }

    /**
     * Adds a feed to the planet
     * @param PlanetFeed feed
     */
    public function addPerson(&$feed)
    {
        $this->people[] = $feed;
    }

    /**
     * Load people from an OPML
     * @return integer Number of people loaded
     */
    public function loadOpml($file)
    {
        if (!is_file($file)) {
            $this->errors[] = new PlanetError(3, $file.' is missing.');
            return 0;
        }

        $opml = OpmlManager::load($file);
        $opml_people = $opml->getPeople();
        foreach ($opml_people as $opml_person){
            $this->addPerson(
                new PlanetFeed(
                    $opml_person['name'],
                    $opml_person['feed'],
                    $opml_person['website']
                )
            );
        }
        return count($opml_people);
    }

    /**
     * Load feeds
     */
    public function loadFeeds()
    {
        foreach ($this->people as $person) {
            $person->set_timeout(-1);
            $person->init();
            $this->items = array_merge($this->items, $person->get_items());
        }

        $this->sort();
    }

    /**
     * Download
     * @var $max_load percentage of feeds to load
     */
    public function download($max_load=0.1)
    {

        $max_load_feeds = ceil(count($this->people) * $max_load);

        foreach ($this->people as $person) {
            //Avoid mass loading with variable cache duration
            //$person->set_cache_duration($this->config->getCacheTimeout()+rand(0,30));
            $person->set_cache_duration($this->config->getCacheTimeout());

            //Load only a few feeds, force other to fetch from the cache
            if (0 > $max_load_feeds--) {
                $person->set_timeout(-1);
                $this->errors[] = new PlanetError(1, 'Forced from cache : '.$person->getFeed());
            }

            //Load feed
            $person->init();

            // http://simplepie.org/wiki/reference/simplepie/merge_items ?
            //Add items to index
            if (($person->data) && ($person->get_item_quantity() > 0)){
                $items = $person->get_items();
                $this->items = array_merge($this->items, $items);
            } else {
                $this->errors[] = new PlanetError(1, 'No items : '.$person->getFeed());
            }
        }
    }

    public function sort()
    {
        usort($this->items, array('PlanetItem','compare'));
    }
}
