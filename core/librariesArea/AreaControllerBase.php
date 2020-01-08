<?php

/**
 * Controller Base Class
 * Responsible for
 * 1- Load Models
 * 2- Load Views
 *
 * We Will make most of ControllerBase class public {!protected} to allow direct unit testing
 */

class AreaControllerBase {

    public function __construct($area = '')
    {
        $this->area = $area;
        $this->model_name = static::class . 'Model';
    }

    protected  $title = '';
    protected $area = '';
    protected $model_name = '';
    // Load Model : loading Model file to allow dynamically use it from controller actions
    // then we must call $this->loadModel(); before creating new model and passing it
    // we can call it from the constructor , or we will need to call it with every action
    public function loadModel($name = '') : void {
        if ($name) {
            $this->model_name = $name;
        }
        require_once __SPECIFICATION_APP_LOCATION__ . __DEFAULT_AREAS_PATH__ . $this->area . '/' . __DEFAULT_AREA__MODELS_PATH__ . $this->model_name . '.php';
    }

    protected function setActionTitle(string $title): void
    {
        $GLOBALS[__GLOB__CONTROLLER_ACTION_TITLE__] = $title;
    }
    // Load View
    public function view(object $model = null, $view = ''): void
    {
        // $model : will available in any view
        if ($view === ''){
            // get view with the same name as method called this method from a child class
            // to reduce memory usage from debug_backtrace
            $dbt=debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,2);
            $caller = $dbt[1]['function'];
            if ($caller) {
                $view = $caller;
            } else{
                die('Invalid view name');
            }
        }
        // static::class : get class who called this method /
//        $view = __SPECIFICATION_CORE_LOCATION__ . 'views/' . static::class . '/' . $view . '.php';
        $view = __SPECIFICATION_APP_LOCATION__ . __DEFAULT_AREAS_PATH__ . $this->area . '/' . __DEFAULT_AREA__VIEWS_PATH__ . static::class . '/' . $view . '.php';
        if (file_exists($view)){
            $GLOBALS[__GLOB__BODY__] = $this->render_php($view, $model);
            $GLOBALS[__GLOB__CONTROLLER_TITLE__] = $this->title;

            // new layout system
            $this->include_layout();
        } else {
            die('View [' .$view . '] does not exist');
        }
    }

    private function render_php($path, object $model = null){
        // passing array $model :  will make model available in this context
        // Also because of Intellisense problems , we will save the model in the globals
        // $model will still available

        $GLOBALS[__GLOB__MODEL__] = $model;
        ob_start();
        include($path);
        return ob_get_clean(); // = ob_get_contents() & ob_end_clean()
    }

    private function include_layout(){

        echo 'layout ', $this->area;
        // If the current Areas/AreaName/Views/ControllerName/ contains _layout : use it
        $layout = __SPECIFICATION_APP_LOCATION__ . __DEFAULT_AREAS_PATH__ . $this->area . '/' . __DEFAULT_AREA__VIEWS_PATH__ . static::class . '/' . __LAYOUT__;
        if (file_exists($layout)) {
            include_once $layout;
            return;
        }

        // If Areas/AreaName/Views/  contains _layout : use it
        $layout = __SPECIFICATION_APP_LOCATION__ . __DEFAULT_AREAS_PATH__ . $this->area . '/' . __DEFAULT_AREA__VIEWS_PATH__  . __LAYOUT__;
        if (file_exists($layout)) {
            include_once $layout;
            return;
        }


        // if else include main _layout
        $layout = __SPECIFICATION_APP_LOCATION__ . 'views/' . __LAYOUT__;
        if (file_exists($layout)) {
            include_once $layout;
            return;
        }
        // if we reach here : means no layout in the application

        die('You Must Specify any layout for your app');

    }

}
