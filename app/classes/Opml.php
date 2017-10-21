<?php

class Opml
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