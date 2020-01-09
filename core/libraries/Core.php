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

class Core{
    protected $currentController = __DEFAULT_CURRENT_CONTROLLER_NAME__;
    protected $currentMethod = __DEFAULT_CURRENT_METHOD_NAME__;
    protected $params = [];

    public function __construct(Container $container)
    {
        $urlArray = get_url_slugs();

        // Load controller , The first value of the $urlArray
        // -> due to .htaccess rules , current location is ./public/index.php .
        if (isset($urlArray[0]) ) {
            // every controller must start with UpperCase Letter
            $controller = ucwords($urlArray[0]);
            if (
            file_exists(__SPECIFICATION_APP_LOCATION__ . __DEFAULT_CONTROLLERS_PATH__ . $controller . '.php')) {
                $this->currentController = $controller;
                // free location
                unset($urlArray[0]);
            }
        }

        require_once __SPECIFICATION_APP_LOCATION__ . __DEFAULT_CONTROLLERS_PATH__ . $this->currentController . '.php';
        $this->currentController = $container->resolve($this->currentController);

        // load method [action]
        if (isset($urlArray[1])) {
            $method = $urlArray[1];
            if (method_exists($this->currentController, $method)) {
                $this->currentMethod = $method;
            }
            unset($urlArray[1]);
        }

        // Get parameters , the rest of the array
        // empty array produce false condition
        $this->params= $urlArray ? array_values($urlArray) : [];

        // Call [action]
        $container->execute_func_arr($this->currentController, $this->currentMethod, $this->params);

    }
}
