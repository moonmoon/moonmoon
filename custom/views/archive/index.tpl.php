<?php
$count = 0;
$today = Array();
$week = Array();
$month = Array();
$older = Array();
$now = time();

foreach ($items as $item) {
    $age = ($now - $item->get_date('U')) / (60*60*24);
    if ($age < 1) {
        $today[] = $item;
    } elseif ($age < 7) {
        $week[] = $item;
    } elseif ($age < 30) {
        $month[] = $item;
    } else {
        $older[] = $item;
    }
}

header('Content-type: text/html; charset=UTF-8');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$conf['locale']?>" lang="<?=$conf['locale']?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="Content-Style-Type" content="text/css" />

    <title><?php echo $PlanetConfig->getName(); ?></title>
    <?php include(__DIR__.'/head.tpl.php'); ?>
</head>

<body>
    <div id="page">
        <?php include(__DIR__.'/top.tpl.php'); ?>

        <div id="content">
            <?php if (0 == count($items)) :?>
            <div class="article">
                <h2 class="article-title">
                    <?=_g('No article')?>
                </h2>
                <p class="article-content"><?=_g('No news, good news.')?></p>
            </div>
            <?php endif; ?>
            <?php if (count($today)): ?>
            <div class="article">
                <h2><?=_g('Today')?></h2>
                <ul>
                <?php foreach ($today as $item): ?>
                    <?php $feed = $item->get_feed(); ?>
                    <li>
                    <a href="<?php echo $feed->getWebsite() ?>" class="source"><?php echo $feed->getName() ?></a> :
                    <a href="<?php echo $item->get_permalink(); ?>" title="<?=_g('Go to original place')?>"><?php echo $item->get_title(); ?></a>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php if (count($week)): ?>
            <div class="article">
                <h2><?=_g('This week')?></h2>
                <ul>
                <?php foreach ($week as $item): ?>
                    <?php $feed = $item->get_feed(); ?>
                    <li>
                    <a href="<?php echo $feed->getWebsite() ?>" class="source"><?php echo $feed->getName() ?></a> :
                    <a href="<?php echo $item->get_permalink(); ?>" title="<?=_g('Go to original place')?>"><?php echo $item->get_title(); ?></a>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php if (count($month)): ?>
            <div class="article">
                <h2><?=_g('This month')?></h2>
                <ul>
                <?php foreach ($month as $item): ?>
                    <?php $feed = $item->get_feed(); ?>
                    <li>
                    <a href="<?php echo $feed->getWebsite() ?>" class="source"><?php echo $feed->getName() ?></a> :
                    <a href="<?php echo $item->get_permalink(); ?>" title="<?=_g('Go to original place')?>"><?php echo $item->get_title(); ?></a>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php if (count($older)): ?>
            <div class="article">
                <h2><?=_g('Older items')?></h2>
                <ul>
                <?php foreach ($older as $item): ?>
                    <?php $feed = $item->get_feed(); ?>
                    <li>
                    <a href="<?php echo $feed->getWebsite() ?>" class="source"><?php echo $feed->getName() ?></a> :
                    <a href="<?php echo $item->get_permalink(); ?>" title="Go to original place"><?php echo $item->get_title(); ?></a>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>

        <?php include_once(__DIR__.'/sidebar.tpl.php'); ?>

        <?php include(__DIR__.'/footer.tpl.php'); ?>
    </div>
</body>
</html>
