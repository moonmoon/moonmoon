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

include(dirname(__FILE__).'/../lib/lib.opml.php');
include(dirname(__FILE__).'/../lib/simplepie/simplepie.inc');
include(dirname(__FILE__).'/../lib/spyc-0.2.3/spyc.php');

/**
 * Planet configuration class
 */
class PlanetConfig{
    var $conf;
    
    function __construct($array){
        $defaultConfig = Array(
            'url' => 'http://www.example.com/',
            'name' => '',
            'items' => 10,
            'shuffle' => 0,
            'refresh' => 240,
            'cache' => 10,
            'nohtml' => 0,
            'postmaxlength' => 0,
            'cachedir' => './cache',
            'categoryfilter' => null
        );
        
        //User config
        $this->conf = $array;
        
        //Complete config with default config
        foreach ($defaultConfig as $key => $value){
            if (!isset($this->conf[$key])){
                $this->conf[$key] = $value;
            }
        }
    }
    
    function getUrl(){
        return $this->conf['url'];
    }

    function getName(){
        return $this->conf['name'];
    }

    function setName($name){
        $this->conf['name'] = $name;
    }
    
    function getCacheTimeout(){
        return $this->conf['refresh'];
    }

    function getOutputTimeout(){
        return $this->conf['cache'];
    }

    //@TODO: drop this pref
    function getShuffle(){
        return $this->conf['shuffle'];
    }
    
    function getMaxDisplay(){
        return $this->conf['items'];
    }

    //@TODO: drop this pref
    function getNoHTML(){
        return $this->conf['nohtml'];
    }

    //@TODO: drop this pref
    function getPostMaxLength(){
        return $this->conf['postmaxlength'];
    }

    function getCategoryFilter(){
        return $this->conf['categoryfilter'];
    }
    
    function toYaml(){
        return Spyc::YAMLDump($this->conf,4);
    }
}

/**
 * Planet person
 */
class PlanetPerson extends SimplePie{
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

/**
 * Planet, main app class
 */
class Planet{
    var $config;
    var $items;
    var $people;
    
    var $errors;
    
    function Planet($config=null) {
        if ($config == null){
            $this->config = new PlanetConfig(array());
        }
        else{
            $this->config = $config;
        }
        $this->items = array();
        $this->people = array();
        $this->errors = array();
    }
    
    /**
     * Getter for all items
     * 
     * If categoryfilter is defined in config.yml,
     * only return items matching this category/tag (case insensitive)
     */
    function getItems() {
        if (!is_null($category = $this->config->getCategoryFilter())) {
            $category = strtolower($category);
            foreach ($this->items as $k => $v) {
                $found = false;
                $cats  = $v->get_categories();
                if (is_array($cats)) {
                    foreach ($cats as $v) {
                        if (strtolower($c->get_label()) == $category) {
                            $found = true;
                            break;
                        }
                    }
                }
                if (!$found) {
                    unset($this->items[$k]);
                }
            }
            $this->items = array_values($this->items);
        }
        return $this->items;
    }

    /**
     * Getter
    */
    function getPeople() {
        return $this->people;
    }
    
    /**
     * Adds a person to the planet
     * @param PlanetPerson person
     */
    function addPerson(&$person) {
        $this->people[] = $person;
    }
    
    /**
     * Load people from an OPML
     * @return integer Number of people loaded
     */
    function loadOpml($file) {
        if (!is_file($file)){
            $this->errors[] = new PlanetError(3, $file.' is missing.');
            return 0;
        }
        $opml = OpmlManager::load($file);
        $opml_people = $opml->getPeople();
        foreach ($opml_people as $opml_person){
            $this->addPerson(
                new PlanetPerson(
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
    function loadFeeds() {
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
    function download($max_load=0.1){
        
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
    
    function sort() {
        usort($this->items, array('PlanetItem','compare'));
    }
}
?>
