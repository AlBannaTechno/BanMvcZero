<?php

class Payment extends AreaControllerBase
{
    public function __construct($area = '')
    {
        parent::__construct($area);
        $this->title = 'Home';
        $this->loadModel();
        $xdb = PdoDatabase::getInstance();
        $x =$xdb->query('SELECT * FROM pdoworks.users')->execute()->resultSet();
//        var_dump($x);
    }

    public function index(){
        $this->setActionTitle('Main');
        $this->view();
    }


    public function buy($id, $name, PdoDatabase $c){
        $this->setActionTitle('Buy');
        $this->view(new PaymentModel($id, $name, 55));
    }

}
