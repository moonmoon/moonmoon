<?php
class opml
{
    var $_xml = null;
    var $_currentTag = '';

    var $title = '';
    var $entries = array();
    var $map  =
        array(
            'URL'         => 'website',
            'HTMLURL'     => 'website',
            'TEXT'        => 'name',
            'TITLE'       => 'name',
            'XMLURL'      => 'feed',
            'DESCRIPTION' => 'description',
            'ISDOWN'      => 'isDown'
            );


    function parse($data)
    {
        $this->_xml = xml_parser_create('UTF-8');
        //xml_parser_set_option($this->_xml, XML_OPTION_CASE_FOLDING, false);
        //xml_parser_set_option($this->_xml, XML_OPTION_SKIP_WHITE, true);
        xml_set_object($this->_xml, $this);
        xml_set_element_handler($this->_xml,'_openTag','_closeTag');
        xml_set_character_data_handler ($this->_xml, '_cData');

        xml_parse($this->_xml,$data);
        xml_parser_free($this->_xml);
        return $this->entries;
    }


    function _openTag($p,$tag,$attrs)
    {
        $this->_currentTag = $tag;

        if ($tag == 'OUTLINE')
        {
            $i = count($this->entries);
            foreach (array_keys($this->map) as $key)
            {
                if (isset($attrs[$key])) {
                    $this->entries[$i][$this->map[$key]] = $attrs[$key];
                }
            }
        }
    }

    function _closeTag($p, $tag){
        $this->_currentTag = '';
    }

    function _cData($p, $cdata){
        if ($this->_currentTag == 'TITLE'){
            $this->title = $cdata;
        }
    }

    function getTitle(){
        return $this->title;
    }

    function getPeople(){
        return $this->entries;
    }
}

class OpmlManager
{
    static function load($file) {
        if (@file_exists($file)) {
            $opml = new opml();

            //Remove BOM if needed
            $BOM = '/^﻿/';
            $fileContent = file_get_contents($file);
            $fileContent = preg_replace($BOM, '', $fileContent, 1);

            //Parse
            $opml->parse($fileContent);

            return $opml;
        }
    }

    public function save($opml, $file){
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

    public function backup($file){
        copy($file, $file.'.bak');
    }
}
