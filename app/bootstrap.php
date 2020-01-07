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
