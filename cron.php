<?php
include_once(__DIR__.'/app/app.php');

//Load OPML
if (0 < $Planet->loadOpml(__DIR__.'/custom/people.opml')) {
    $Planet->download(1.0);
}

if ($conf['debug'] === true) {
    foreach ($Planet->errors as $error) {
        echo $error->toString() . "\n";
    }
}