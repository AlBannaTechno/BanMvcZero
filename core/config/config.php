<?php
// application directory related to core directory
define('__SPECIFICATION_APP_LOCATION__',  '../site/');
define('__SPECIFICATION_CORE_LOCATION__',  '../core/');
// Import secrets
include_once __SPECIFICATION_APP_LOCATION__ . 'Secrets.php';

// App Root
define('APP_ROOT', dirname(__FILE__, 2));

// URL ROOT

define('URL_ROOT', 'http://localhost/BanMVC');

// SITE NAME

define('SITE_NAME', 'BanMvcImp');

// define The Main Layout Name , from ../app/views/

define('__LAYOUT__', '_layout.php');
define('__AREA_DEFAULTS__', '_defaults.php');
define('__AREA_BASE_DEFAULTS__', '_defaults.php');

// declare specific globals => globals return string to other globals

define('__GLOB__BODY__', '__BODY__');
define('__GLOB__CONTROLLER_TITLE__', '__CONTROLLER_TITLE__');
define('__GLOB__CONTROLLER_ACTION_TITLE__', '__CONTROLLER_ACTION_TITLE__');
define('__GLOB__MODEL__', '__MODEL__');
define('__GLOB__AREA__DEFAULT_CONTROLLER', '__AREA_DEFAULT_CONTROLLER__');

// __AREA_DEFAULT_AREA__ : only will valid if
// ___ROUTING_SYSTEM_AREAS__ is on top of __CORE_DEFAULT_ROUTING_SYSTEMS__
// otherwise either page[& default] or controller [& default] will take this place
define('__GLOB__AREA_BASE__DEFAULT_AREA__', '__AREA_DEFAULT_AREA__');

// Config CORE FEATURES

define('__CORE_FEATURES_SUPPORT_AREA__', TRUE);

define('___ROUTING_SYSTEM_AREAS__', 'AREA');
define('___ROUTING_SYSTEM_CONTROLLERS__', 'CONTROLLER');
define('___ROUTING_SYSTEM_PAGES__', 'PAGES');


define('__CORE_DEFAULT_ROUTING_SYSTEMS__', [
    ___ROUTING_SYSTEM_PAGES__,
    ___ROUTING_SYSTEM_CONTROLLERS__,
    ___ROUTING_SYSTEM_AREAS__
]);

define('__DEFAULT_PAGE__', 'index.php');
define('__DEFAULT_FALLBACK__DIR_PATH', 'Fallback');
define('__DEFAULT_FALLBACK__404_PAGE__', 'page404.php');

define('__DEFAULT_AREAS_PATH__','Areas/');
define('__DEFAULT_AREA__VIEWS_PATH__','Views/');
define('__DEFAULT_AREA__CONTROLLERS_PATH__','Controllers/');
define('__DEFAULT_AREA__MODELS_PATH__','Models/');
define('__DEFAULT_PAGES_PATH__','Pages/');
define('__DEFAULT_CONTROLLERS_PATH__','controllers/');
define('__DEFAULT_VIEWS_PATH__','views/');
