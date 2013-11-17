<?php
include_once(dirname(__FILE__).'/app/app.php');

if (!isset($Planet)) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 503 Service Unavailable', true, 503);
    echo "moonmoon is not configured";
    die();
}

//Load OPML
if (0 < $Planet->loadOpml(dirname(__FILE__).'/custom/people.opml')) {
    $Planet->download();
}

var_dump($Planet->errors);
