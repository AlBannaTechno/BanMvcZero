<?php

/**
 * Class Model
 *
 * Any Model class should extends this class to support Intellisense
 * Then Call ModeClassName::load() , and assign it to any variable
 * Also notice : $model , still available in any view , but we can override it
 * And also notice , $model still override but without Intellisense
 *
 * This is the only way i figure it to force PHP to work correctly in this generic scenario
 * [PHP does not support generics]
 */
class AreaModel {
    public static function load() : self {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Heleprs\Model\loadModel();
    }
}
