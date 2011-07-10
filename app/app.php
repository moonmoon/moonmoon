<?php

//Debug ?
$debug = isset($_GET['debug']) ? $_GET['debug'] : 0;
if ($debug) {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

include_once(dirname(__FILE__).'/classes/Planet.class.php');

if (is_file(dirname(__FILE__).'/../custom/config.yml')){
    $conf = Spyc::YAMLLoad(dirname(__FILE__).'/../custom/config.yml');
    $PlanetConfig = new PlanetConfig($conf);
    $Planet = new Planet($PlanetConfig);
}
