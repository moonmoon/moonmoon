<?php

/**
 * Planet configuration class
 */
class PlanetConfig
{

    protected $conf = array();

    protected $defaultConfig = array(
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

    /**
     * PlanetConfig constructor.
     * @param array $userConfig
     * @param bool  $useDefaultConfig
     */
    public function __construct($userConfig = [], $useDefaultConfig = true)
    {
        $default = $useDefaultConfig ? $this->defaultConfig : array();
        $this->conf = $this->merge($default, $userConfig);
    }

    /**
     * Merge the configuration of the user in the default one.
     *
     * @param array $default
     * @param array $user
     * @return array
     */
    protected function merge($default, $user)
    {
        return array_merge($default, $this->normalizeArrayKeys($user));
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

    /**
     * @deprecated
     * @return mixed
     */
    public function getShuffle()
    {
        return $this->conf['shuffle'];
    }

    public function getMaxDisplay()
    {
        return $this->conf['items'];
    }

    /**
     * @deprecated
     * @return mixed
     */
    public function getNoHTML()
    {
        return $this->conf['nohtml'];
    }

    /**
     * @deprecated
     * @return mixed
     */
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
        return Spyc::YAMLDump($this->conf, 4);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->conf;
    }

    public function getDebug()
    {
        return $this->conf['debug'];
    }

    /**
     * @return array
     */
    public function getDefaultConfig()
    {
        return $this->defaultConfig;
    }

    /**
     * Normalize the name of a configuration key.
     *
     * @param  string $key
     * @return string
     */
    protected function normalizeKeyName($key = null)
    {
        return strtolower($key);
    }

    /**
     * Normalize all the keys of the array.
     *
     * @param  array $array
     * @return array
     */
    protected function normalizeArrayKeys($array = [])
    {
        foreach ($array as $key => $value) {
            $normalized = $this->normalizeKeyName($key);
            if ($normalized !== $key) {
                $array[$this->normalizeKeyName($key)] = $value;
                unset($array[$key]);
            }
        }

        return $array;
    }

    /**
     * Generic configuration getter.
     *
     * @return mixed|null
     */
    public function __get($key)
    {
        $key = $this->normalizeKeyName($key);

        return array_key_exists($key, $this->conf) ?
            $this->conf[$key] :
            null;
    }

    /**
     * Generic configuration setter.
     *
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $key = $this->normalizeKeyName($key);

        $this->conf[$key] = $value;
    }

}
