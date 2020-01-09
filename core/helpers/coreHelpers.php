<?php
namespace Helpers\Core;

function get_url_slugs() : array
{
    // we configure it in .htaccess , so the rest of the request URL will save in url variable location
    // so we can request http://localhost/BanMVC/?url=controller/method/params
    // or request http://localhost/BanMVC/controller/method/params
    // it's the same

    if (isset($_GET[__DEFAULT_SERVER_URL_PARAM_NAME__])){
        // remove any '/' from the right side of the url
        $url =  rtrim($_GET[__DEFAULT_SERVER_URL_PARAM_NAME__], '/');
        // Sanitize url , remove any characters does not belong tu url standard
        $url = filter_var($url, FILTER_SANITIZE_URL);
        // convert url string into an array based on '/' as a separator
        $url = explode( '/', $url);
        return $url;
    }
    return [];
}

function get_url(): string {
    if (isset($_GET[__DEFAULT_SERVER_URL_PARAM_NAME__])){
        $url =  rtrim($_GET[__DEFAULT_SERVER_URL_PARAM_NAME__], '/');
        // Sanitize url , remove any characters does not belong tu url standard
        $url = filter_var($url, FILTER_SANITIZE_URL);
        return $url;
    }
    return '';
}

// We will only use this function if we decide to support query parameters
// eg. ?x=23&run=Play
// we need to modify this function to use less memory
function get_url_params() : array {
    $arr = array();
    // warn #bug : $_GET return duplicated values
    foreach ($_GET as $key => $value) {
        $arr[$key] = $value;
    }
    return array_unique($arr);
}

/**
 * @return array : all query params except url
 *
 */
function get_url_query_params(){
    $params = get_url_params();
    unset($params[__DEFAULT_SERVER_URL_PARAM_NAME__]);
    return $params;
}
