<?php
include_once(dirname(__FILE__).'/app/app.php');
include_once(dirname(__FILE__).'/app/lib/Cache.php');

//Load from cache
$items = Array();
if (0 < $Planet->loadOpml(dirname(__FILE__).'/custom/people.opml')) {
    $Planet->loadFeeds();
    $items = $Planet->getItems();
}

$items = $storage->getAll();

header("Content-type: text/html;charset=UTF-8");


$sources = OpmlManager::load(dirname(__FILE__).'/custom/people.opml');
function getSource($url, $sources) {
	// print_r($sources);
	foreach($sources->entries as $source) {
		// echo $source['feed'];
		if ($source['feed'] == $url) {
			return $source['name'];
		}
	}
}

foreach ($items as $item) {
	$source = parse_url($item['permalink']);
	echo $item['date'] . " <a href='".$item['permalink']."' id='".$item['guid']."'>" . $item['title'] . "</a> " . getSource($item['feed_url'], $sources) . '<br>';
	// print_r($item);
}