<?php

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
            'cachedir' => './cache'
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

    function toYaml(){
        return Spyc::YAMLDump($this->conf,4);
    }
}