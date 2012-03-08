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

if (is_file(dirname(__FILE__).'/../custom/config.yml')){
    $conf = Spyc::YAMLLoad(dirname(__FILE__).'/../custom/config.yml');
    $PlanetConfig = new PlanetConfig($conf);
    $Planet = new Planet($PlanetConfig);
}
