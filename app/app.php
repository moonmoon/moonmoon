<?php

error_reporting(0);

require_once __DIR__.'/../vendor/autoload.php';

$auth = new Authentication();

$savedConfig  = __DIR__.'/../custom/config.yml';
$moon_version = file_get_contents(__DIR__.'/../VERSION');

if (is_installed()) {
    $conf = Spyc::YAMLLoad($savedConfig);

    // this is a check to upgrade older config file without l10n
    if(!isset($conf['locale'])) {
        $resetPlanetConfig = new PlanetConfig($conf);
        file_put_contents($savedConfig, $resetPlanetConfig->toYaml());
        $conf = Spyc::YAMLLoad($savedConfig);
    }

    $PlanetConfig = new PlanetConfig($conf);
    $Planet = new Planet($PlanetConfig);

    if ($conf['debug']) {
        error_reporting(E_ALL);
    }

}

$l10n = new Simplel10n($conf['locale']);

// this is an helper function. We will usually use that function and not Simplel10n::getString()
function _g($str, $comment='') {
    return Simplel10n::getString($str);
}

function custom_path($file = '')
{
    return __DIR__.'/../custom' . (!empty($file) ? '/'.$file : '');
}

function is_installed()
{
    return file_exists(custom_path('config.yml')) && file_exists(custom_path('people.opml'));
}
