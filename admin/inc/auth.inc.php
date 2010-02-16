<?php
include (dirname(__FILE__).'/pwd.inc.php');

if ( isset($_COOKIE['auth']) && $_COOKIE['auth'] == $password ) {
    //ok, cool
} else {
    setcookie('auth','', time()-3600);
    header('Location: login.php');
    die;
}
?>