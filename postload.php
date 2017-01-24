<?php
include_once(__DIR__.'/app/app.php');

$Planet->addPerson(
    new PlanetFeed(
        '',
        htmlspecialchars_decode($_GET['url'], ENT_QUOTES),
        '',
        false
    )
);

//Load feeds
$Planet->download(1);
header("Content-type: image/png");
readfile(__DIR__."/custom/img/feed.png");
die();
