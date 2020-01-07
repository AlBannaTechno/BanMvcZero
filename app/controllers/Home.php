<?php


class Home extends ControllerBase
{
    public function __construct()
    {
    }

    public function index(){
        $this->view(['title' => 'Welcome From Index Of Home']);
    }

    public function about($id, $name){
    }

}
