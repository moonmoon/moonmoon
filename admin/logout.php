<?php

require_once __DIR__ . '/../app/app.php';

setcookie('auth','', time()-3600);
session_destroy();
session_regenerate_id();

header('Location: login.php');
die();
