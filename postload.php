<?php

require_once __DIR__.'/app/app.php';

if (!is_installed()) {
    die();
}

$xml = new SimpleXMLElement(file_get_contents(custom_path('people.opml')));

foreach ($xml->xpath('/opml/body/outline[@xmlUrl]') as $element)
{
    if ($element->attributes()->xmlUrl == $_GET['url'])
    {
        $Planet->addPerson(
            new PlanetFeed(
                '',
                $_GET['url'],
                '',
                false
            )
        );

        $Planet->download(1);
        header('Content-type: image/png');
        readfile(custom_path('img/feed.png'));
        die();
    }
}

echo 'Updating this URL is not allowed.';