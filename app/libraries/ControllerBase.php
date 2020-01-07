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

    // Load Model
    public function model($model){
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    // Load View
    // $model : will available in any view
    public function view($model = [], $view = '') {
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
            include_once $view;
        } else {
            die('View [' .$view . '] does not exist');
        }
    }

}
