<?php

//Debug ?
$debug = isset($_GET['debug']) ? $_GET['debug'] : 0;
if ($debug) {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

require_once __DIR__.'/../vendor/autoload.php';

$savedConfig  = dirname(__FILE__).'/../custom/config.yml';
$moon_version = file_get_contents(dirname(__FILE__).'/../VERSION');

if (is_file($savedConfig)){

    $conf = Spyc::YAMLLoad($savedConfig);

    // this is a check to upgrade older config file without l10n
    if(!isset($conf['locale'])) {
        $resetPlanetConfig = new PlanetConfig($conf);
        file_put_contents($savedConfig, $resetPlanetConfig->toYaml());
        $conf = Spyc::YAMLLoad($savedConfig);
    }

    $PlanetConfig = new PlanetConfig($conf);
    $Planet = new Planet($PlanetConfig);
}

$l10n = new Simplel10n($conf['locale']);

// this is an helper function. We will usually use that function and not Simplel10n::getString()
function _g($str, $comment='') {
    return Simplel10n::getString($str);
}
