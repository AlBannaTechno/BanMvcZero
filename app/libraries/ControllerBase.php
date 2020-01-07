<?php

/**
 * Controller Base Class
 * Responsible for
 * 1- Load Models
 * 2- Load Views
 *
 * We Will make most of ControllerBase class public {!protected} to allow direct unit testing
 */

class ControllerBase {

    protected  $title = '';
    // Load Model
    public function model($model){
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    protected function setActionTitle(string $title){
        $GLOBALS[__GLOB__CONTROLLER_ACTION_TITLE__] = $title;
    }
    // Load View
    public function view(object $model = null, $view = '') {
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
        $view = '../app/views/' . static::class . '/' . $view . '.php';
        if (file_exists($view)){
            $GLOBALS[__GLOB__BODY__] = $this->render_php($view, $model);
            $GLOBALS[__GLOB__CONTROLLER_TITLE__] = $this->title;
            include_once '../app/views/' . __LAYOUT__;
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

}
