<?php
include_once(__DIR__.'/app/app.php');
include_once(__DIR__.'/app/lib/Cache.php');

//Installed ?
if (!isset($Planet)) {
    echo '<p>' . _g('You might want to <a href="install.php">install moonmoon</a>.') . '</p>';
    exit;
}

//Load from cache
$items = Array();
if (0 < $Planet->loadOpml(__DIR__.'/custom/people.opml')) {
    $Planet->loadFeeds();
    $items = $Planet->getItems();
}

//Prepare output cache
Cache::$enabled = false;
$cache_key      = (count($items)) ? $items[0]->get_id()   : '';
$last_modified  = (count($items)) ? $items[0]->get_date() : '';
$cache_duration = $PlanetConfig->getOutputTimeout()*60;

Cache::setStore(__DIR__ . '/' . $conf['cachedir'] . '/');

if (isset($_GET['type']) && $_GET['type'] == 'atom10') {
    /* XXX: Redirect old ATOM feeds to new url to make sure our users don't
     * loose subscribers upon upgrading their moonmoon installation.
     * Remove this check in a more distant future.
     */
    header('Status: 301 Moved Permanently', false, 301);
    header('Location: atom.php');
    exit;
}

//Go display
if (!isset($_GET['type']) ||
    !is_file(__DIR__.'/custom/views/'.$_GET['type'].'/index.tpl.php') ||
    strpos($_GET['type'], DIRECTORY_SEPARATOR)){
    $_GET['type'] = 'default';
}

if (!OutputCache::Start($_GET['type'], $cache_key, $cache_duration)) {
    include_once(__DIR__.'/custom/views/'.$_GET['type'].'/index.tpl.php');
    OutputCache::End();
}

if ($conf['debug'] === true) {
    echo "<!-- \$Planet->errors:\n";
    var_dump($Planet->errors);
    echo "-->";
}
