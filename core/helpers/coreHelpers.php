<?php
namespace Helpers\Core;

use ReflectionClass;
use ReflectionException as ReflectionExceptionAlias;

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


/**
 * Since all php string to array or string matcher implementations use linear or binary search
 * it will be not efficient to depend on it when resolving [area] name from the controller location
 * since we know exactly the depth between two elements , so we will implement it
 * @param string $controller_path
 * @return string
 */
function get_area_from_controller_path(string $controller_path) : string {
    try {
        $rc = new ReflectionClass($controller_path);
        $path = dirname($rc->getFileName(),2);
        // note : I still confused about using negative indices in php string , due to php implementation
        // means if i access string negative index many times , php will check count first
        // so for multi access eg, our case, it's will be more efferent to get count once and then use it
        // https://wiki.php.net/rfc/negative-index-support
        // https://wiki.php.net/rfc/negative-string-offsets
        $length = strlen($path);
        for($c = $length -1 ; $c > -1 ; $c--){
            if ($path[$c] === '\\'){
                return substr($path, $c+1);
            }
        }
    } catch (ReflectionExceptionAlias $e) {
    }
    return '';
}
