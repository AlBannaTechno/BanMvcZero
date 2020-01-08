<?php
include_once '../app/models/AboutModel.php';

class Home extends ControllerBase
{
    public function __construct()
    {
        $this->title = 'Home';
    }

    public function index(){
        $this->setActionTitle('Index');
        $this->view(new class(){
            public $message  = "Index Here";
        });
    }

    // we may need to set default values for action parameters otherwise , php will show error
    // if passed arguments [url parameters] count is < action[function] parameters count
    public function about($id, $name){
        $aboutModel = new AboutModel($id, $name);
        $this->view($aboutModel);
    }

}
