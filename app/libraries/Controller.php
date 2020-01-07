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

    public function view($view, $data = []) {
        // static::class : get class who called this method / child
        $view = '../app/views/' . static::class . '/' . $view . '.php';
        if (file_exists($view)){
            include_once $view;
        } else {
            die('View [' .$view . '] does not exist');
        }
    }

}
