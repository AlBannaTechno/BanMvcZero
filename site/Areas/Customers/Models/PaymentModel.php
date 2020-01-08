<?php

class PaymentModel extends AreaModel
{
    public $id;
    public $name;
    public $price;

    public function __construct($id = -1, $name = '', $price = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }
}
