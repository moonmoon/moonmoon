<?php
include_once(dirname(__FILE__).'/app/app.php');

//Load OPML
if (0 < $Planet->loadOpml(dirname(__FILE__).'/custom/people.opml')) {
    $Planet->download();
}

var_dump($Planet->errors);
