<?php
include_once(dirname(__FILE__).'/app/classes/Planet.class.php');
include_once(dirname(__FILE__).'/app/lib/Cache.php');
include_once(dirname(__FILE__).'/app/lib/lib.http.php');

//Load configuration
if (is_file(dirname(__FILE__).'/custom/config.yml')){
    $conf = Spyc::YAMLLoad(dirname(__FILE__).'/custom/config.yml');
    $PlanetConfig = new PlanetConfig($conf);
} else {
    die('Config file (custom/config.yml) is missing.');
}

//Instantiate app
$Planet = new Planet($PlanetConfig);

//Load OPML
if (0 < $Planet->loadOpml(dirname(__FILE__).'/custom/people.opml')) {
    $Planet->download(1.0);
}

var_dump($Planet->errors);
