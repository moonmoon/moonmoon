<?php
include_once(dirname(__FILE__).'/app/app.php');
include_once(dirname(__FILE__).'/app/lib/Cache.php');

//Installed ?
if (!isset($Planet)) {
    echo '<p>' . _g('You might want to <a href="install.php">install moonmoon</a>.') . '</p>';
    exit;
}

//Load from cache
$items = Array();
if (0 < $Planet->loadOpml(dirname(__FILE__).'/custom/people.opml')) {
    $Planet->loadFeeds();
    $items = $Planet->getItems();
}

//Prepare output cache
Cache::$enabled = false;
$cache_key      = (count($items)) ? $items[0]->get_id()   : '';
$last_modified  = (count($items)) ? $items[0]->get_date() : '';
$cache_duration = $PlanetConfig->getOutputTimeout()*60;

Cache::setStore(dirname(__FILE__) . '/' . $conf['cachedir'] . '/');

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

echo "<!--";
var_dump($Planet->errors);
echo "-->";
