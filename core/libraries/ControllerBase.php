<?php

/**
 * Controller Base Class
 * Responsible for
 * 1- Load Models
 * 2- Load Views
 *
 * We Will make most of ControllerBase class public {!protected} to allow direct unit testing
 */

abstract class ControllerBase {

    protected  $title = '';
    protected $model_name = '';

    public function __construct()
    {
        $this->model_name = static::class . 'Model';
    }

    // TODO : change $params usage implementation to support query params ?x=23&y=2 , when we support QP in the core
    public static function link(string $name = '', $params = '') : string {
        return URL_ROOT . '/' . static::class . '/' .$name . '/' . $params;
    }

    // Load Model
    public function loadModel($name = '') : void {
        if ($name) {
            $this->model_name = $name;
        }
        require_once __SPECIFICATION_APP_LOCATION__ . __DEFAULT_CONTROLLERS_PATH__  . '/'  . $this->model_name . '.php';
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
        $view = __SPECIFICATION_APP_LOCATION__ . __DEFAULT_VIEWS_PATH__ . static::class . '/' . $view . '.php';
        if (file_exists($view)){
            $GLOBALS[__GLOB__BODY__] = $this->render_php($view, $model);
            $GLOBALS[__GLOB__CONTROLLER_TITLE__] = $this->title;
            include_once $this->get_current_layout();
        } else {
            die('View [' .$view . '] does not exist');
        }
    }

    private function render_php($path, object $model){
        // passing array $model :  will make model available in this context
        // Also because of Intellisense problems , we will save the model in the globals
        // $model will still available

        $GLOBALS[__GLOB__MODEL__] = $model;
        ob_start();
        include($path);
        return ob_get_clean(); // = ob_get_contents() & ob_end_clean()
    }


    private function get_current_layout(): ?string
    {
        $layout = __SPECIFICATION_APP_LOCATION__ . __DEFAULT_VIEWS_PATH__ . static::class . '/' . __LAYOUT__;
        if (file_exists($layout)){
            return $layout;
        }
        return  __SPECIFICATION_APP_LOCATION__ . __DEFAULT_VIEWS_PATH__ . __LAYOUT__;
    }
}
