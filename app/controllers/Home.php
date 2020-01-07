<?php


class Home
{
    public function __construct()
    {
        echo '[Home]{INIT}';
    }

    public function index(){
        echo '[[Index] Of [Home]]';
    }

    public function about($id, $name){
        echo '[[about] Of [Home]] ' . $id . ' ' . $name;
    }

}
