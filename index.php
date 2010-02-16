<?php

//Do not do anything before install
if (is_file(dirname(__FILE__).'/install.php')) {
    echo '<p>You might want to <a href="install.php">install moonmoon</a>.<br/>If not, <strong>delete</strong> <code>install.php</code>.</p>';
    die;
}

$bench['start'] = microtime(true);

$debug = isset($_GET['debug']) ? $_GET['debug'] : 0;
if ($debug) {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

include_once(dirname(__FILE__).'/app/classes/Planet.class.php');
include_once(dirname(__FILE__).'/app/lib/Cache.php');

//Load configuration
if (is_file(dirname(__FILE__).'/custom/config.yml')){
    $conf = Spyc::YAMLLoad(dirname(__FILE__).'/custom/config.yml');
    $PlanetConfig = new PlanetConfig($conf);
} else {
    die('Config file (custom/config.yml) is missing.');
}

//Instantiate app
$Planet = new Planet($PlanetConfig);
$bench['codeloaded'] = microtime(true);

//Load from cache
$items = Array();
if (0 < $Planet->loadOpml(dirname(__FILE__).'/custom/people.opml')) {
    $Planet->loadFeeds();
    $items = $Planet->getItems();
}
$bench['contentloaded'] = microtime(true);

//Prepare output cache
Cache::$enabled = false;
$cache_key = (count($items)) ? $items[0]->get_id() : '';
$last_modified = (count($items)) ? $items[0]->get_date() : '';
$cache_duration = $PlanetConfig->getOutputTimeout()*60;
Cache::setStore(dirname(__FILE__).'/'.$conf['cachedir']);

//Go display
if (!isset($_GET['type']) || 
    !is_file(dirname(__FILE__).'/custom/views/'.$_GET['type'].'/index.tpl.php') || 
    strpos($_GET['type'], DIRECTORY_SEPARATOR)){
    $_GET['type'] = 'default';
}

if (!OutputCache::Start($_GET['type'], $cache_key, $cache_duration)) {
    include_once(dirname(__FILE__).'/custom/views/'.$_GET['type'].'/index.tpl.php');
    OutputCache::End();
}

$bench['contentdisplayed'] = microtime(true);

echo "<!-- Load code: ".($bench['codeloaded'] - $bench['start'])." -->";
echo "<!-- Load content: ".($bench['contentloaded'] - $bench['codeloaded'])." -->";
echo "<!-- Display: ".($bench['contentdisplayed'] - $bench['contentloaded'])." -->";
echo "<!--";
var_dump($Planet->errors);
echo "-->";