<?php

require_once __DIR__ . '/../app/app.php';

$auth->redirectIfNotAuthenticated();

if (isset($_POST['password'])) {
    $auth->changePassword($_POST['password']);
    redirect('administration.php');
}

die('The new password cannot be empty!');
