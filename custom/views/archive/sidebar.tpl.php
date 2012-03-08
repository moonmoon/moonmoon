<?php
$all_people = &$Planet->getPeople();
usort($all_people, array('PlanetFeed', 'compare'));
?>
<div id="sidebar">
    <div id="sidebar-people">
        <h2>People (<?php echo count($all_people); ?>)</h2>
        <ul>
            <?php foreach ($all_people as $person) : ?>
            <li>
                <a href="<?php echo htmlspecialchars($person->getFeed(), ENT_QUOTES, 'UTF-8'); ?>" title="Feed"><img src="postload.php?url=<?php echo urlencode(htmlspecialchars($person->getFeed(), ENT_QUOTES, 'UTF-8')); ?>" alt="feed" height="12" width="12" /></a>
                <a href="<?php echo $person->getWebsite(); ?>" title="Website"><?php echo htmlspecialchars($person->getName(), ENT_QUOTES, 'UTF-8'); ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
        <p>
        <a href="custom/people.opml"><img src="custom/img/opml.png" alt="feed" height="12" width="12" />&nbsp;All feeds in OPML format</a>
        </p>
    </div>
    
    <div>
        <h2>Syndicate</h2>
        <ul>
            <li><img src="custom/img/feed.png" alt="feed" height="12" width="12" />&nbsp;<a href="?type=atom10">Feed (ATOM)</a></li>
        </ul>
    </div>

    <div>
        <h2>Archives</h2>
        <ul>
            <li><a href="?type=archive">See all headlines</a></li>
        </ul>
    </div>
</div>