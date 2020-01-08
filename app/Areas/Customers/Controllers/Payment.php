<?php
include_once '../app/Areas/Customers/Models/PaymentModel.php';

class Payment extends AreaControllerBase
{
    public function __construct($area = '')
    {
        parent::__construct($area);
        $this->title = 'Home';
    }

    public function index(){
        $this->setActionTitle('Main');
        $this->view();
    }


    public function buy($id, $name){
        $this->setActionTitle('Buy');
        $this->view(new PaymentModel($id, $name, 55));
    }

}
