<?php

// Loading Config
require_once 'config/config.php';

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
        $dbt=debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,2);
        $caller = $dbt[1]['function'];
        require_once 'librariesArea/' . $className . '.php';
    });
} else {
    spl_autoload_register(static function($className){
        require_once 'libraries/' . $className . '.php';
    });
}


// include helpers
// here we must not include any optional helpers , only VI helpers
// for example layout helpers will used always so we need it

include '../app/helpers/layoutHelpers.php';
include '../app/helpers/modelHelpers.php';
