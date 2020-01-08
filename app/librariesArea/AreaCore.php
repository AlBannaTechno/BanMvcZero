<?php
/**
 * Core MVC Class
 * This class is responsible for
 * 1- dealing with url [Url Mapping]
 * 2- Load controllers based on url
 *
 * Url Format : /controller/method/params
 */

class AreaCore{
    protected $currentController = 'Home';
    protected $currentMethod = 'index';
    protected $currentArea = 'Main'; // can not use public , reversed for apache
    protected $params = [];

    public function __construct()
    {
        $urlArray = $this->getUrl();
//        print_r($urlArray);

        // Load Area
        if (isset($urlArray[0]) ) {
            $this->currentArea = ucwords($urlArray[0]);
            unset($urlArray[0]);
        }
//        print_r($this->currentArea);

        // All next conditions is implicitly false if the previous is false : TODO we should deal with this behaviour
        // Load controller , The first value of the $urlArray
        // -> due to .htaccess rules , current location is ./public/index.php .
        if (isset($urlArray[1]) ) {
            // every controller must start with UpperCase Letter
            $controller = ucwords($urlArray[1]);
            if (
//            file_exists('../app/controllers/' . $controller . '.php')) {
            file_exists('../app/Areas/'.$this->currentArea.'/Controllers/'. $controller . '.php')) {
                $this->currentController = $controller;
                // free location
                unset($urlArray[1]);
            }

        } else {
            // no area provided
            $this->check_area_defaults();
        }

        require_once '../app/Areas/'.$this->currentArea.'/Controllers/'. $this->currentController . '.php';
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
    final public function getUrl() : array
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

    // check if _default.php exist , to load defaults from it
    private function check_area_defaults(): void {
        $defaults = '../app/Areas/' . $this->currentArea . '/' . __AREA_DEFAULTS__;
        if (file_exists($defaults)) {
            include_once $defaults;
            if (isset($GLOBALS[__GLOB__AREA__DEFAULT_CONTROLLER])) {
                $this->currentController = $GLOBALS[__GLOB__AREA__DEFAULT_CONTROLLER];
            }
        }

    }
}
