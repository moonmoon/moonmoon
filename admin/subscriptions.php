<?php
require_once dirname(__FILE__).'/inc/auth.inc.php';

include_once(dirname(__FILE__).'/../app/classes/Planet.class.php');
include_once(dirname(__FILE__).'/../app/lib/spyc-0.2.3/spyc.php');
require_once dirname(__FILE__).'/../app/lib/lib.opml.php';
require_once dirname(__FILE__).'/../app/lib/simplepie/simplepie.inc';

function removeSlashes(&$item, $key){
    $item = stripslashes($item);
}

if (isset($_POST['opml']) || isset($_POST['add'])) {

    // Load config and old OPML
    $conf = Spyc::YAMLLoad(dirname(__FILE__).'/../custom/config.yml');
    $PlanetConfig = new PlanetConfig($conf);
    if ($PlanetConfig->getName() === '') {
        $PlanetConfig->setName($oldOpml->getTitle());
    }
    $oldOpml = OpmlManager::load(dirname(__FILE__).'/../custom/people.opml');
    $newOpml = new opml();
    $newOpml->title = $PlanetConfig->getName();

    // Remove slashes if needed
    if (get_magic_quotes_gpc() && isset($_POST['opml'])) {
        array_walk_recursive($_POST['opml'], 'removeSlashes');
    }
    // Delete/Save feeds
    if (isset($_POST['delete']) || isset($_POST['save'])){
        foreach ($_POST['opml'] as $person){
            if (isset($_POST['delete'])) {
                //delete mode, check if to be deleted
                if (!isset($person['delete'])){
                    $newOpml->entries[] = $person;
                }
            } else {
                $newOpml->entries[] = $person;
            }
        }
    }
    
    // Add feed
    if (isset($_POST['add'])){
        if ('http://' != $_POST['url']) {
            //autodiscover feed
            $feed = new SimplePie();
            $feed->enable_cache(false);
            $feed->set_feed_url($_POST['url']);
            $feed->init();
            $feed->handle_content_type();
            $person['name'] = $feed->get_title();
            $person['website'] = $feed->get_permalink();
            $person['feed'] = $feed->feed_url;

            $oldOpml->entries[] = $person;
            $newOpml->entries = $oldOpml->entries;
        }
    }

    // Backup old OPML
    OpmlManager::backup(dirname(__FILE__).'/../custom/people.opml');
    
    // Save new OPML
    OpmlManager::save($newOpml, dirname(__FILE__).'/../custom/people.opml');
}
header("Location: index.php");
die();