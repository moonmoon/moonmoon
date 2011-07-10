<?php
include_once(dirname(__FILE__).'/app/app.php');

function unhtmlspecialchars( $string ) {
    $string = str_replace ( '&amp;', '&', $string );
    $string = str_replace ( '&#039;', '\'', $string );
    $string = str_replace ( '&quot;', '\"', $string );
    $string = str_replace ( '&lt;', '<', $string );
    $string = str_replace ( '&gt;', '>', $string );
   
    return $string;
}

$Planet->addPerson(
    new PlanetPerson(
        '',
        unhtmlspecialchars($_GET['url']),
        ''
    )
);

//Load feeds
$Planet->download(1);
header("Content-type: image/png");
readfile(dirname(__FILE__)."/custom/img/feed.png");
die();