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

        // load controllers
        $file = __DEFAULT_CONTROLLERS_PATH__ . '/' . $className . '.php'  ;
        if (file_exists(__SPECIFICATION_APP_LOCATION__ . '' . $file)) {
            require_once $file;
            return;
        }

        // load area
        $area_controllers_location = __SPECIFICATION_APP_LOCATION__ . '/' . __DEFAULT_AREAS_PATH__ . '/*'
            . __DEFAULT_AREA__CONTROLLERS_PATH__ . '/' .$className . '.php';

        $area_controllers = glob($area_controllers_location);
        if ($area_controllers){
            require_once $area_controllers[0];
        }
    });
} else {
    spl_autoload_register(static function($className){
        require_once 'libraries/' . $className . '.php';
    });
}

if (__CORE_FEATURES_SUPPORT_AUTO_LOAD_WITHS_STARTUP__) {

    // load controllers
    $controllers_location = __SPECIFICATION_APP_LOCATION__ . '/' . __DEFAULT_CONTROLLERS_PATH__ . '/*.php';
    $controllers = glob($controllers_location);
    foreach ($controllers as $key => $controller){
        require_once $controller;
    }

    // load area controllers
    $area_controllers_location = __SPECIFICATION_APP_LOCATION__ . '/' . __DEFAULT_AREAS_PATH__ . '/*/'
        . __DEFAULT_AREA__CONTROLLERS_PATH__ . '/*.php';
    $area_controllers = glob($area_controllers_location);
    foreach ($area_controllers as $key => $area_controller){
        require_once $area_controller;
    }
}

// include helpers
// here we must not include any optional helpers , only VI helpers
// for example layout helpers will used always so we need it

include __SPECIFICATION_CORE_LOCATION__ . 'helpers/layoutHelpers.php';
include __SPECIFICATION_CORE_LOCATION__ . 'helpers/modelHelpers.php';
include __SPECIFICATION_CORE_LOCATION__ . 'helpers/coreHelpers.php';
include __SPECIFICATION_CORE_LOCATION__ . 'Exceptions/ModelException.php';
include __SPECIFICATION_CORE_LOCATION__ . 'IoC/Container.php';

function register_core_types(Container $container){

    $container->provide(CoresProvider::class);
    $container->provide(ControllerBase::class);
    $container->provide(Core::class);
    $container->provide(Model::class);
    $container->provide(AreaControllerBase::class);
    $container->provide(AreaCore::class);
    $container->provide(AreaModel::class);
    $container->register_with_factory(PdoDatabase::class, static function ($container, $opt_params){
        return PdoDatabase::getInstance();
    });
    // TODO implement provideWith
    // TODO allow optional parameters to not passed in Container
    $container->provide(AreaMapper::class, ['strictMode' => true], Container::REG_TYPE_SINGLETON);
}
