<?php

namespace Heleprs\Model{

    use BanMvc\Exceptions\ModelException;

    function loadModel() : object {
        $model =  $GLOBALS[__GLOB__MODEL__];
        if (!$model){
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new ModelException('You need to pass a model to the view before using it');
        }
        return $model;
    }
}
