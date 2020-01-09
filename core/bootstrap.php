<?php

// Global exception handler
set_exception_handler(function ($exception){
    echo '<pre>';
    /** @noinspection ForgottenDebugOutputInspection */
    print_r($exception);
    echo '</pre>';
});
// Loading Config
require_once 'config/config.php';
require_once 'CoresProvider.php';
// Loading Libraries
//require_once 'libraries/Core.php';
//require_once 'libraries/ControllerBase.php';
//require_once 'libraries/Database.php';

//require_once 'librariesArea/AreaCore.php';
//require_once 'librariesArea/AreaControllerBase.php';
//require_once 'librariesArea/AreaModel.php';



// Auto Load Core Libraries
// All ClassNames need to match File name


if (__CORE_FEATURES_SUPPORT_AREA__){
    spl_autoload_register(static function($className){
        $file = 'librariesArea/' . $className . '.php';
        if (file_exists(__SPECIFICATION_CORE_LOCATION__ . '' . $file)){
            require_once $file;
            return;
        }
        $file = 'libraries/' . $className . '.php';
        if (file_exists(__SPECIFICATION_CORE_LOCATION__ . '' . $file)) {
            require_once $file;
            return;
        }
        $file = 'database/' . $className . '.php';
        if (file_exists(__SPECIFICATION_CORE_LOCATION__ . '' . $file)) {
            require_once $file;
            return;
        }
    });
} else {
    spl_autoload_register(static function($className){
        require_once 'libraries/' . $className . '.php';
    });
}


// include helpers
// here we must not include any optional helpers , only VI helpers
// for example layout helpers will used always so we need it

include __SPECIFICATION_CORE_LOCATION__ . 'helpers/layoutHelpers.php';
include __SPECIFICATION_CORE_LOCATION__ . 'helpers/modelHelpers.php';
include __SPECIFICATION_CORE_LOCATION__ . 'helpers/coreHelpers.php';
include __SPECIFICATION_CORE_LOCATION__ . 'Exceptions/ModelException.php';
include __SPECIFICATION_CORE_LOCATION__ . 'IoC/Container.php';
