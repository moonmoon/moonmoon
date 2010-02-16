<?php
$limit = $PlanetConfig->getMaxDisplay();
$count = 0;

header('Content-Type: text/plain; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8" ?>';
?><feed xmlns="http://www.w3.org/2005/Atom">
    <title><?php echo $PlanetConfig->getName(); ?></title>
    <subtitle></subtitle>
    <id><?php echo $PlanetConfig->getUrl(); ?></id>
    <link rel="self" type="text/html" href="<?php echo $PlanetConfig->getUrl(); ?>?type=atom10" />
    <link rel="alternate" type="text/html" href="<?php echo $PlanetConfig->getUrl(); ?>" />
    <updated><?php echo date("Y-m-d\TH:i:s\Z") ?></updated>
    <author><name>Author</name></author>
  
    <?php $count = 0; ?>
    <?php foreach ($items as $item): ?>
    <entry xmlns="http://www.w3.org/2005/Atom">
        <title type="html"><?php echo htmlspecialchars($item->get_feed()->getName()); ?> : <?php echo htmlspecialchars($item->get_title());?></title>
        <id><?php echo htmlspecialchars($item->get_permalink());?></id>
        <link rel="alternate" href="<?php echo htmlspecialchars($item->get_permalink());?>"/>
        <published><?php echo $item->get_date('Y-m-d\\TH:i:s+00:00'); ?></published>
        <author><name><?php echo ($item->get_author()? $item->get_author()->get_name() : 'anonymous'); ?></name></author>
        
        <content type="html"><![CDATA[<?php echo $item->get_content();?>]]></content>
    </entry>
    <?php if (++$count == $limit) { break; } ?>
    <?php endforeach; ?>
</feed>