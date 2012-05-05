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

var_dump($PlanetConfig, $Planet);
$limit = $PlanetConfig->getMaxDisplay();
$count = 0;

header('Content-Type: application/atom+xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8" ?>';
?><feed xmlns="http://www.w3.org/2005/Atom">
    <title><?php echo htmlspecialchars($PlanetConfig->getName()); ?></title>
    <subtitle><?php echo htmlspecialchars($PlanetConfig->getName()); ?></subtitle>
    <id><?php echo $PlanetConfig->getUrl(); ?></id>
    <link rel="self" type="application/atom+xml" href="<?php echo $PlanetConfig->getUrl(); ?>atom.php" />
    <link rel="alternate" type="text/html" href="<?php echo $PlanetConfig->getUrl(); ?>" />
    <updated><?php echo date("Y-m-d\TH:i:s\Z") ?></updated>
    <author><name>Author</name></author>

    <?php $count = 0; ?>
    <?php foreach ($items as $item): ?>
    <entry>
        <title type="html"><?php echo htmlspecialchars($item->get_feed()->getName()); ?> : <?php echo htmlspecialchars($item->get_title());?></title>
        <id><?php echo htmlspecialchars($item->get_permalink());?></id>
        <link rel="alternate" href="<?php echo htmlspecialchars($item->get_permalink());?>"/>
        <published><?php echo $item->get_date('Y-m-d\\TH:i:s+00:00'); ?></published>
        <updated><?php echo $item->get_date('Y-m-d\\TH:i:s+00:00'); ?></updated>
        <author><name><?php echo ($item->get_author()? $item->get_author()->get_name() : 'anonymous'); ?></name></author>

        <content type="html"><![CDATA[<?php echo $item->get_content();?>]]></content>
    </entry>
    <?php if (++$count == $limit) { break; } ?>
    <?php endforeach; ?>
</feed>
