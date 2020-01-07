<?php

// Loading Config
require_once 'config/config.php';

// Loading Libraries
//require_once 'libraries/Core.php';
//require_once 'libraries/ControllerBase.php';
//require_once 'libraries/Database.php';


// Auto Load Core Libraries
// All ClassNames need to match File name
spl_autoload_register(static function($className){
    require_once 'libraries/' . $className . '.php';
});

// include helpers
// here we must not include any optional helpers , only VI helpers
// for example layout helpers will used always so we need it

include '../app/helpers/layoutHelpers.php';
include '../app/helpers/modelHelpers.php';
