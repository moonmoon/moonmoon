<?php

include dirname(__FILE__).'/pwd.inc.php';
require_once __DIR__.'/../../app/classes/Planet.class.php';

if (!Planet::authenticateUser($_COOKIE['auth'], $password)) {
    setcookie('auth', '', time() - 3600);
    header('Location: login.php');
    die();
}
