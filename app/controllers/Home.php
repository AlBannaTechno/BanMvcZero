<?php


class Home extends ControllerBase
{
    public function __construct()
    {
        echo '[Home]{INIT}';
        echo '<br/>';
    }

    public function index(){
        echo '[[Index] Of [Home]]';
        $this->view('index');
    }

    public function about($id, $name){
        echo '[[about] Of [Home]] ' . $id . ' ' . $name;
    }

}
