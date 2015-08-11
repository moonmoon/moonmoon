<?php

include dirname(__FILE__).'/pwd.inc.php';

if (!class_exists('Planet')) {
    require __DIR__.'/../../vendor/autoload.php';
}

if (!Planet::authenticateUser($_COOKIE['auth'], $password)) {
    setcookie('auth', '', time() - 3600);
    header('Location: login.php');
    die();
}
