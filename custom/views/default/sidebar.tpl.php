<?php
$all_people = &$Planet->getPeople();
usort($all_people, array('PlanetPerson', 'compare'));
?>
<div id="sidebar" class="aside">
    <div id="sidebar-people" class="section">
        <h2>People (<?php echo count($all_people); ?>)</h2>
        <ul>
            <?php foreach ($all_people as $person) : ?>
            <li>
                <a href="<?php echo htmlspecialchars($person->getFeed(), ENT_QUOTES, 'UTF-8'); ?>" title="Feed"><img src="postload.php?url=<?php echo urlencode(htmlspecialchars($person->getFeed(), ENT_QUOTES, 'UTF-8')); ?>" alt="" height="12" width="12" /></a>
                <a href="<?php echo $person->getWebsite(); ?>" title="Website"><?php echo htmlspecialchars($person->getName(), ENT_QUOTES, 'UTF-8'); ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
        <p>
        <img src="custom/img/opml.png" alt="feed" height="12" width="12" /> <a href="custom/people.opml">All feeds in OPML format</a>
        </p>
    </div>
    
    <div class="section">
        <h2>Syndicate</h2>
        <ul>
            <li><img src="custom/img/feed.png" alt="feed" height="12" width="12" />&nbsp;<a href="?type=atom10">Feed (ATOM)</a></li>
        </ul>
    </div>

    <div class="section">
        <h2>Archives</h2>
        <ul>
            <li><a href="?type=archive">See all headlines</a></li>
        </ul>
    </div>
</div>