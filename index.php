<?php
include_once(dirname(__FILE__).'/app/app.php');

//Installed ?
if (!isset($Planet)) {
    echo '<p>' . _g('You might want to <a href="install.php">install moonmoon</a>.') . '</p>';
    exit;
}

//Load from cache
$items = array();
$feeds = array();
if (0 < $Planet->loadOpml(dirname(__FILE__).'/custom/people.opml')) {
    $Planet->loadFeeds();
    $items = $Planet->getItems();
    
    $feeds = &$Planet->getPeople();
    usort($feeds, array('PlanetFeed', 'compare'));
}

if (!isset($_GET['type'])) {
    header('Content-type: text/html; charset=UTF-8');
    echo $twig->render('index.twig', array(
        'config' => $PlanetConfig,
        'items' => array_slice($items, 0, $PlanetConfig->getMaxDisplay()),
        'feeds' => $feeds
    ));
} else {
    if ('archive' === $_GET['type']) {
        header('Content-type: text/html; charset=UTF-8');
        echo $twig->render('archive.twig', array(
            'config' => $PlanetConfig,
            'items' => $items,
            'feeds' => $feeds
        ));
    }
    if ('atom10' === $_GET['type']) {
        /* XXX: Redirect old ATOM feeds to new url to make sure our users don't
         * loose subscribers upon upgrading their moonmoon installation.
         * Remove this check in a more distant future.
         */
        header('Status: 301 Moved Permanently', false, 301);
        header('Location: atom.php');
        exit;
    }
}

echo "<!--";
var_dump($Planet->errors);
echo "-->";
