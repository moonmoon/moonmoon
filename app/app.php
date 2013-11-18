<?php
require __DIR__ . '/../vendor/autoload.php';
include(dirname(__FILE__).'/lib/lib.opml.php');
include(dirname(__FILE__).'/lib/spyc-0.5/spyc.php');

include_once(dirname(__FILE__).'/classes/PlanetConfig.php');
include_once(dirname(__FILE__).'/classes/PlanetFeed.php');
include_once(dirname(__FILE__).'/classes/PlanetItem.php');
include_once(dirname(__FILE__).'/classes/PlanetItemStorage.php');
include_once(dirname(__FILE__).'/classes/PlanetError.php');
include_once(dirname(__FILE__).'/classes/Planet.class.php');
include_once(dirname(__FILE__).'/classes/Simplel10n.class.php');

$savedConfig  = dirname(__FILE__).'/../custom/config.yml';
$moon_version = file_get_contents(dirname(__FILE__).'/../VERSION');
$db = __DIR__ . "/../custom/feeds.sqlite";

if (is_file($savedConfig)){

    $conf = Spyc::YAMLLoad($savedConfig);

    // this is a check to upgrade older config file without l10n
    if(!isset($conf['locale'])) {
        $resetPlanetConfig = new PlanetConfig($conf);
        file_put_contents($savedConfig, $resetPlanetConfig->toYaml());
        $conf = Spyc::YAMLLoad($savedConfig);
    }

    $PlanetConfig = new PlanetConfig($conf);

    // Connect to database
    if ('sqlite' === $PlanetConfig->getStorage()) {
        try {
            $storage = new PlanetItemStorage($db);
        } catch (Exception $e) {
            error_log("Couldn't open database: " . $e->getMessage());
            $storage = null;
        }
    } else {
        $storage = null;
    }

    //Instantiate Planet
    $Planet = new Planet($PlanetConfig, $storage);

    //Initialize translation
    $l10n = new Simplel10n($conf['locale']);
}

// this is an helper function. We will usually use that function and not Simplel10n::getString()
function _g($str, $comment='') {
    return Simplel10n::getString($str);
}
$l10n_filter = new Twig_SimpleFilter('g', function ($string) {
    return _g($string);
});

// Initialize Twig
$loader = new Twig_Loader_Filesystem(__DIR__ . '/../custom/views');
$twig = new Twig_Environment($loader);
$twig->addFilter($l10n_filter);
