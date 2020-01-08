<?php
namespace Helpers\Core;

function get_url_slugs() : array
{
    // we configure it in .htaccess , so the rest of the request URL will save in url variable location
    // so we can request http://localhost/BanMVC/?url=controller/method/params
    // or request http://localhost/BanMVC/controller/method/params
    // it's the same

    if (isset($_GET['url'])){
        // remove any '/' from the right side of the url
        $url =  rtrim($_GET['url'], '/');
        // Sanitize url , remove any characters does not belong tu url standard
        $url = filter_var($url, FILTER_SANITIZE_URL);
        // convert url string into an array based on '/' as a separator
        $url = explode( '/', $url);
        return $url;
    }
    return [];
}
