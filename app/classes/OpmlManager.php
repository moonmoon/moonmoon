<?php


class OpmlManager
{
    public static function load($file)
    {
        if (!file_exists($file)) {
            throw new Exception('OPML file not found!');
        }

        $opml = new opml();

        //Remove BOM if needed
        $BOM = '/^﻿/';
        $fileContent = file_get_contents($file);
        $fileContent = preg_replace($BOM, '', $fileContent, 1);

        //Parse
        $opml->parse($fileContent);

        return $opml;
    }

    /**
     * @param Opml   $opml
     * @param string $file
     */
    public static function save($opml, $file){
        $out = '<?xml version="1.0"?>'."\n";
        $out.= '<opml version="1.1">'."\n";
        $out.= '<head>'."\n";
        $out.= '<title>'.htmlspecialchars($opml->getTitle()).'</title>'."\n";
        $out.= '<dateCreated>'.date('c').'</dateCreated>'."\n";
        $out.= '<dateModified>'.date('c').'</dateModified>'."\n";
        $out.= '</head>'."\n";
        $out.= '<body>'."\n";
        foreach ($opml->entries as $person) {
            $out.= '<outline text="' . htmlspecialchars($person['name'], ENT_QUOTES) . '" htmlUrl="' . htmlspecialchars($person['website'], ENT_QUOTES) . '" xmlUrl="' . htmlspecialchars($person['feed'], ENT_QUOTES) . '" isDown="' . htmlspecialchars($person['isDown'], ENT_QUOTES) . '"/>'."\n";
        }
        $out.= '</body>'."\n";
        $out.= '</opml>';

        file_put_contents($file, $out);
    }

    public static function backup($file){
        copy($file, $file.'.bak');
    }
}
