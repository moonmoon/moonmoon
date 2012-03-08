<?php
$limit = $PlanetConfig->getMaxDisplay();
$count = 0;

header('Content-type: text/html; charset=UTF-8');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="Content-Style-Type" content="text/css" />

    <title><?php echo $PlanetConfig->getName(); ?></title>
    <?php include(dirname(__FILE__).'/head.tpl.php'); ?>
</head>

<body>
    <script type="text/javascript">
    document.body.className += 'js';
    </script>
    <div id="page">
        <?php include(dirname(__FILE__).'/top.tpl.php'); ?>
        
        <div id="content">
            <?php if (0 == count($items)) : ?>
                <div class="article">
                    <h2 class="article-title">
                        No article
                    </h2>
                    <p class="article-content">No news, good news.</p>
                </div>
            <?php else : ?>
                <?php foreach ($items as $item): ?>
                    <?php 
                    $arParsedUrl = parse_url($item->get_feed()->getWebsite());
                    $host = 'from-' . preg_replace('/[^a-zA-Z0-9]/i', '-', $arParsedUrl['host']);
                    ?>
                    <div class="article <?php echo $host; ?>">
                        <h2 class="article-title">
                            <a href="<?php echo $item->get_permalink(); ?>" title="Go to original place"><?php echo $item->get_title(); ?></a>
                        </h2>
                        <p class="article-info">
                            
                            <?php echo ($item->get_author()? $item->get_author()->get_name() : 'Anonymous'); ?>,
                            <?php 
                            $ago = time() - $item->get_date('U');
                            //echo '<span title="'.Duration::toString($ago).' ago" class="date">'.date('d/m/Y', $item->get_date('U')).'</span>';
                            echo '<span id="post'.$item->get_date('U').'" class="date">'.$item->get_date('d/m/Y').'</span>';
                            ?>
                            
                            |
                            
                            Source: <?php
                            $feed = $item->get_feed();
                            echo '<a href="'.$feed->getWebsite().'" class="source">'.$feed->getName().'</a>';
                            ?>
                        </p>
                        <div class="article-content">
                            <?php echo $item->get_content(); ?>
                        </div>
                    </div>
                    <?php if (++$count == $limit) { break; } ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <?php include_once(dirname(__FILE__).'/sidebar.tpl.php'); ?>
        
        <?php include(dirname(__FILE__).'/footer.tpl.php'); ?>
    </div>
</body>
</html>
