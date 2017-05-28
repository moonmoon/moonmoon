<?php

/**
 * Planet configuration class
 */
class PlanetConfig
{

    public $conf;

    protected static $defaultConfig = array(
        'url'           => 'http://www.example.com/',
        'name'          => '',
        'locale'        => 'en',
        'items'         => 10,
        'shuffle'       => 0,
        'refresh'       => 240,
        'cache'         => 10,
        'nohtml'        => 0,
        'postmaxlength' => 0,
        'categories'    => '',
        'cachedir'      => './cache',
        'debug'         => false
    );

    public function __construct($array)
    {
        // User config
        $this->conf = $array;

        // Complete config with default config
        foreach (self::$defaultConfig as $key => $value) {
            if (!array_key_exists($key, $this->conf)) {
                $this->conf[$key] = $value;
            }
        }
    }

    public function getUrl()
    {
        return $this->conf['url'];
    }

    public function getName(){
        return $this->conf['name'];
    }

    public function setName($name)
    {
        $this->conf['name'] = $name;
    }

    public function getCacheTimeout()
    {
        return $this->conf['refresh'];
    }

    public function getOutputTimeout()
    {
        return $this->conf['cache'];
    }

    //@TODO: drop this pref
    public function getShuffle()
    {
        return $this->conf['shuffle'];
    }

    public function getMaxDisplay()
    {
        return $this->conf['items'];
    }

    //@TODO: drop this pref
    public function getNoHTML()
    {
        return $this->conf['nohtml'];
    }

    //@TODO: drop this pref
    public function getPostMaxLength()
    {
        return $this->conf['postmaxlength'];
    }

    public function getCategories()
    {
        return $this->conf['categories'];
    }

    public function toYaml()
    {
        return Spyc::YAMLDump($this->conf,4);
    }

    public function getDebug()
    {
        return $this->conf['debug'];
    }

    /**
     * Generic accessor for config.
    */
    public function __get($key)
    {
        $key = strtolower($key);

        return array_key_exists($key, $this->conf) ?
            $this->conf[$key] :
            null;
    }
}
