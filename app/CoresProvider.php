<?php

use function Helpers\Core\get_url_slugs;

class CoresProvider {
    public function __construct()
    {
        $url_slugs = get_url_slugs();
        if (isset($url_slugs[0])){ // area or controller [not an area controller]
            if (file_exists('../app/controllers/' . ucwords($url_slugs[0]))){
                new Core();
            } else {
                new AreaCore();
            }
        } else if (__CORE_DEFAULT_ROUTING_TO_AREAS__) {
            new AreaCore();
        } else{
            new Core();
        }

    }
}
