<?php
require_once dirname(__FILE__).'/inc/auth.inc.php';

if (isset($_POST['purge'])){
    $dir = dirname(__FILE__).'/../cache/';
    
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