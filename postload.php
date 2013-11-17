<?php
include_once(dirname(__FILE__).'/app/app.php');

$Planet->addPerson(
    new PlanetFeed(
        '',
        htmlspecialchars_decode($_GET['url'], ENT_QUOTES),
        ''
    )
);

//Load feeds
$Planet->download();
header("Content-type: image/png");
readfile(dirname(__FILE__)."/custom/img/feed.png");
die();
