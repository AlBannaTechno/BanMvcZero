<?php

use function Helpers\Core\get_url_slugs;

/**
 * Core MVC Class
 * This class is responsible for
 * 1- dealing with url [Url Mapping]
 * 2- Load controllers based on url
 *
 * Url Format : /controller/method/params
 */

class AreaCore{
    protected $currentController = '';
    protected $currentMethod = 'index';
    protected $currentArea = ''; // can not use public , reversed for apache
    protected $params = [];

    public function __construct()
    {
        $urlArray = get_url_slugs();
//        print_r($urlArray);

        // Load Area
        if (isset($urlArray[0]) ) {
            $this->currentArea = ucwords($urlArray[0]);
            unset($urlArray[0]);
        }
        if (!$this->currentArea){
            $this->check_area_base_defaults();
        }
        $this->check_area_defaults();
//        print_r($this->currentArea);

        // All next conditions is implicitly false if the previous is false : TODO we should deal with this behaviour
        // Load controller , The first value of the $urlArray
        // -> due to .htaccess rules , current location is ./public/index.php .
        if (isset($urlArray[1]) ) {
            // every controller must start with UpperCase Letter
            $controller = ucwords($urlArray[1]);
            if (
//            file_exists(__SPECIFICATION_CORE_LOCATION__ . 'controllers/' . $controller . '.php')) {
            file_exists(__SPECIFICATION_APP_LOCATION__ . 'Areas/'.$this->currentArea.'/Controllers/'. $controller . '.php')) {
                $this->currentController = $controller;
                // free location
                unset($urlArray[1]);
            }

        }

        require_once __SPECIFICATION_APP_LOCATION__ . 'Areas/'.$this->currentArea.'/Controllers/'. $this->currentController . '.php';
        $this->currentController = new $this->currentController($this->currentArea);

        // load method [action]
        if (isset($urlArray[2])) {
            $method = $urlArray[2];
            if (method_exists($this->currentController, $method)) {
                $this->currentMethod = $method;
            }
            unset($urlArray[2]);
        }

        // Get parameters , the rest of the array
        // empty array produce false condition
        $this->params= $urlArray ? array_values($urlArray) : [];

        // Call [action]
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);

    }

    // check if _default.php exist , to load defaults from it
    private function check_area_defaults(): void {
        $defaults = __SPECIFICATION_APP_LOCATION__ . 'Areas/' . $this->currentArea . '/' . __AREA_DEFAULTS__;
        if (file_exists($defaults)) {
            include_once $defaults;
            if (isset($GLOBALS[__GLOB__AREA__DEFAULT_CONTROLLER])) {
                $this->currentController = $GLOBALS[__GLOB__AREA__DEFAULT_CONTROLLER];
            }
        }

    }

    private function check_area_base_defaults(): void{
        $defaults = __SPECIFICATION_APP_LOCATION__ . 'Areas/' . __AREA_BASE_DEFAULTS__;
        if (file_exists($defaults)) {
            include_once $defaults;
            if (isset($GLOBALS[__GLOB__AREA_BASE__DEFAULT_AREA__])){
                $this->currentArea = $GLOBALS[__GLOB__AREA_BASE__DEFAULT_AREA__];
            }
        }
    }
}
