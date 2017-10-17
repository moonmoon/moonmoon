<?php

require_once __DIR__ . '/app/app.php';

// This is an helper function returning an html table row to avoid code duplication
function installStatus($str, $msg, $result) {
    $class = ($result) ? 'ok' : 'fail';
    return '<tr><td>' . $str . '</td><td class="' . $class . '">' . $msg . '</td></tr>';
}

// If the config file exists and the auth variables are set, moonmoon is already installed
if (is_installed()) {
    $status = 'installed';
} elseif (isset($_POST['url'])) {

    // Do no try to use the file of an invalid locale
    if (strstr($_POST['locale'], '..') !== false
    || !file_exists(__DIR__ . "/app/l10n/${_REQUEST['locale']}.lang")) {
        $_POST['locale'] = 'en';
    }

    $save = array();
    //Save config file
    $config = array_merge(PlanetConfig::$defaultConfig, [
        'url'    => $_POST['url'],
        'name'   => filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS),
        'locale' => $_POST['locale'],
    ]);

    $CreatePlanetConfig = new PlanetConfig($config);
    $save['config'] = file_put_contents(custom_path('config.yml'), $CreatePlanetConfig->toYaml());

    OpmlManager::save(new Opml(), custom_path('people.opml'));

    //Save password
    $save['password'] = file_put_contents(admin_path('inc/pwd.inc.php'), '<?php $login="admin"; $password="'.md5($_POST['password']).'"; ?>');

    if (0 != ($save['config'] + $save['password'])) {
        $status = 'installed';
    }
} else {

    // We start by malking sure we have PHP5 as a base requirement
    if(version_compare(PHP_VERSION, '5.6.0') >= 0) {
        $strInstall = installStatus('Server is running at least PHP 5.6', 'OK',true);
        $strRecommendation = '';
    } else {
        $strInstall = installStatus('Server is running at least PHP 5.6', 'FAIL',false);
        $strRecommendation = '<li>Check your server documentation to activate at least PHP 5.6</li>';
    }

    // Writable file requirements
    $tests = array(
        '/custom',
        '/custom/people.opml',
        '/admin/inc/pwd.inc.php',
        '/cache',
    );

    // We now test that all required files and directories are writable.
    foreach ($tests as $v) {
        if(touch(__DIR__ . $v)) {
            $strInstall .= installStatus("<code>$v</code> is writable", 'OK', true);
            unlink(__DIR__.$v);
        } else {
            $strInstall .= installStatus("<code>$v</code> is writable", 'FAIL',false);
            $strRecommendation .= "<li>Make <code>$v</code> writable with CHMOD</li>";
        }
    }

    // We can now decide if we install moonmoon or not
    $status = ($strRecommendation != '') ? 'error' : 'install';

}

require_once views_path('install.tpl.php');