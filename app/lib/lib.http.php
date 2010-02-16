<?php
/**
 * Send HTTP 304 when needed, set ETag and Last-Modified else
 * Source : http://annevankesteren.nl/2005/05/http-304
 */
function http_modified($last_modified, $identifier){
    $etag = '"'.md5($last_modified.$identifier).'"';
    $client_etag = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false;
    $client_last_modified = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? trim($_SERVER['HTTP_IF_MODIFIED_SINCE']) : 0;
    $client_last_modified_timestamp = strtotime($client_last_modified);
    $last_modified_timestamp = strtotime($last_modified);
    
    if(($client_last_modified && $client_etag) ? (($client_last_modified_timestamp == $last_modified_timestamp) && ($client_etag == $etag)) : (($client_last_modified_timestamp == $last_modified_timestamp) || ($client_etag == $etag))){
        header('Not Modified',true,304);
        exit();
    }else{
        header('Last-Modified:'.$last_modified);
        header('ETag:'.$etag);
    }
}

//http://simonwillison.net/2003/Apr/23/conditionalGet/
function doConditionalGet($timestamp) {
    // A PHP implementation of conditional get, see 
    //   http://fishbowl.pastiche.org/archives/001132.html
    $last_modified = substr(date('r', $timestamp), 0, -5).'GMT';
    $etag = '"'.md5($last_modified).'"';
    // Send the headers
    header("Last-Modified: $last_modified");
    header("ETag: $etag");
    // See if the client has provided the required headers
    $if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ?
        stripslashes($_SERVER['HTTP_IF_MODIFIED_SINCE']) :
        false;
    $if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ?
        stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) : 
        false;
    if (!$if_modified_since && !$if_none_match) {
        return;
    }
    // At least one of the headers is there - check them
    if ($if_none_match && $if_none_match != $etag) {
        return; // etag is there but doesn't match
    }
    if ($if_modified_since && $if_modified_since != $last_modified) {
        return; // if-modified-since is there but doesn't match
    }
    // Nothing has changed since their last request - serve a 304 and exit
    header('HTTP/1.0 304 Not Modified');
    exit;
}
?>