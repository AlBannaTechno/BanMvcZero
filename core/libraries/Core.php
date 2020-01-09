<?php
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
        $urlArray = $this->getUrl();

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
}
