<?php

//Debug ?
$debug = isset($_GET['debug']) ? $_GET['debug'] : 0;
if ($debug) {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

include(dirname(__FILE__).'/lib/lib.opml.php');
include(dirname(__FILE__).'/lib/simplepie/simplepie.inc');
include(dirname(__FILE__).'/lib/spyc-0.5/spyc.php');

include_once(dirname(__FILE__).'/classes/PlanetConfig.php');
include_once(dirname(__FILE__).'/classes/PlanetFeed.php');
include_once(dirname(__FILE__).'/classes/PlanetItem.php');
include_once(dirname(__FILE__).'/classes/PlanetError.php');
include_once(dirname(__FILE__).'/classes/Planet.class.php');
include_once(dirname(__FILE__).'/classes/Simplel10n.class.php');

$savedConfig = dirname(__FILE__).'/../custom/config.yml';

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
function _g($str) {
    return Simplel10n::getString($str);
}


