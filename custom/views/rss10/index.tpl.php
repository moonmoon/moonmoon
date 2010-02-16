<?php
$limit = $PlanetConfig->getMaxDisplay();
$count = 0;

header('Content-Type: text/xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8" ?>';
?><rdf:RDF
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:cc="http://web.resource.org/cc/"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns="http://purl.org/rss/1.0/">

    <channel rdf:about="<?php echo $PlanetConfig->getUrl(); ?>">
        <title><?php echo $PlanetConfig->getName(); ?></title>
        <description></description>
        <link><?php echo $PlanetConfig->getUrl(); ?></link>
        <dc:language></dc:language>
        <dc:creator></dc:creator>
        <dc:rights></dc:rights>
        <dc:date><?php echo date('Y-m-d\\TH:i:s+00:00'); ?></dc:date>
        <admin:generatorAgent rdf:resource="http://moonmoon.inertie.org/" />
    
        <items>
        <rdf:Seq>
            <?php foreach ($items as $item): ?>
            <rdf:li rdf:resource="<?php echo $item->get_permalink(); ?>"/>
            <?php if (++$count == $limit) { break; } ?>
            <?php endforeach; ?>
        </rdf:Seq>
        </items>
    </channel>
    
    <?php $count = 0; ?>
    <?php foreach ($items as $item): ?>
    <item rdf:about="<?php echo $item->get_permalink();?>">
        <title><?php echo htmlspecialchars($item->get_feed()->getName()) ?> : <?php echo htmlspecialchars($item->get_title());?></title>
        <link><?php echo htmlspecialchars($item->get_permalink());?></link>
        <dc:date><?php echo date('Y-m-d\\TH:i:s+00:00',$item->get_date('U')); ?></dc:date>
        <dc:creator><?php echo htmlspecialchars($item->get_author()? $item->get_author()->get_name() : 'anonymous') ?></dc:creator>
        <description><?php echo htmlspecialchars($item->get_content()); ?></description>
        <content:encoded><![CDATA[<?php echo $item->get_content();?>]]></content:encoded>
    </item>
    <?php if (++$count == $limit) { break; } ?>
    <?php endforeach; ?>
    
</rdf:RDF>