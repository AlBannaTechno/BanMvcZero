<?php

use function Helpers\Core\get_url_slugs;
use function Helpers\Core\get_url;

/**
 * Routing Start From
 * [AreaCore] Area -> AreaController -> AreaControllerView
 * [Core] Controller -> ControllerView
 * [Render] Pages
 *
 */
class CoresProvider
{
    public function __construct()
    {

        $url_slugs = get_url_slugs();
        foreach (__CORE_DEFAULT_ROUTING_SYSTEMS__ as $r_system) {
            if ($r_system === ___ROUTING_SYSTEM_PAGES__) {
                if ($url_slugs){
                    // character case insensitive in windows only
                    $page = get_url();
                    if ($this->check_with_include_single_page($page)){
                        return;
//                        break;
                    }
                } else {
                    $page = __SPECIFICATION_APP_LOCATION__ . 'Pages/' . __DEFAULT_PAGE__;
                    if (file_exists($page)) {
                        include_once $page;
                        return;
//                        break;
                    }
                }
            }
            else if ($r_system === ___ROUTING_SYSTEM_CONTROLLERS__) {
                if ($url_slugs && $this->check__single_controller_available($url_slugs[0])){
                    new Core();
                    return;
//                    break;
                }
            }
            else if ($r_system === ___ROUTING_SYSTEM_AREAS__) {
                if ($url_slugs && $this->check_area_available($url_slugs[0])){
                    new AreaCore();
                    return;
//                    break;
                }
            }
        }

        include_once __SPECIFICATION_APP_LOCATION__ . __DEFAULT_FALLBACK__DIR_PATH . '/' . __DEFAULT_FALLBACK__404_PAGE__;

    }

    private function check_area_available($area): bool
    {
        return is_dir(__SPECIFICATION_APP_LOCATION__ . __DEFAULT_AREAS_PATH__ . ucwords($area));
    }

    private function check__single_controller_available($controller): bool
    {
        return file_exists(__SPECIFICATION_APP_LOCATION__ . 'controllers/' . ucwords($controller) . '.php');
    }

    private function check_with_include_single_page($pagePath): bool
    {
        $page = __SPECIFICATION_APP_LOCATION__ . 'Pages/' . $pagePath . '.php';
        if(file_exists($page)){
            include_once $page;
            return true;
        }
        return false;

    }
}
