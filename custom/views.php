<?php
$views = array(
    'rss10' => array(
        'header' => 'Content-Type: text/xml; charset=UTF-8',
        'prolog' => '<?xml version="1.0" encoding="UTF-8" ?>'."\n",
        'template' => dirname(__FILE__).'/views/rss10/rss10.tpl.php'
    ),
    'atom10' => array(
        //'header' => 'Content-Type: text/xml; charset=UTF-8',
        'header' => 'Content-Type: text/plain; charset=UTF-8',
        'prolog' => '<?xml version="1.0" encoding="UTF-8" ?>'."\n",
        'template' => dirname(__FILE__).'/views/atom10/atom10.tpl.php'
    ),
    'archive' => array(
        'header' => 'Content-type: text/html; charset=UTF-8',
        'prolog' => '',
        'template' => dirname(__FILE__).'/views/archive/archive.tpl.php'
    ),
    'html' => array(
        'header' => 'Content-type: text/html; charset=UTF-8',
        'prolog' => '',
        'template' => dirname(__FILE__).'/views/default/index.tpl.php'
    )
);