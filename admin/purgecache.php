<?php

require_once __DIR__.'/../app/app.php';

$auth->redirectIfNotAuthenticated();

if (isset($_POST['purge'])){
    $dir = __DIR__.'/../cache/';

    $dh = opendir($dir);

    while ($filename = readdir($dh)) {
        if ($filename == '.' OR $filename == '..') {
            continue;
        }

        if (filemtime($dir . DIRECTORY_SEPARATOR . $filename) < time()) {
            @unlink($dir . DIRECTORY_SEPARATOR . $filename);
        }
    }
}

header('Location: administration.php');
die();
