<?php

/**
 * Class AreaMapper
 * This Class is responsible for mapping area to virtual area and vis versa
 */
class AreaMapper
{
    /**
     * @var array $_areas [real_area, [virtual_area1, virtual_area2 ,..]]
     * @var bool  $_strictMode , if this option is true , then you must map All Areas
     * if you forget , you can not get the same input if area mapper does not match
     * example ['Customers' => ['Client']]
     * in non strict mode if you pass 'Client' to real_area() you will get Customer
     * but if you pass Customer Also will get Customer , so you can use get area by its real name
     * or by its alternatives
     * But in strict mode , only if you pass Clients you will get Customers , if you pass any thing including Customers
     * itself you will not get any thing
     */
    private $_areas = [];
    private $_strictMode;
    public function __construct($strictMode = false)
    {
        $this->_strictMode = $strictMode;
    }

    public function map_real_area_to(string $real_area,string $virtual_area) : void {
        if (!isset($this->_areas[$real_area])){
            $this->_areas[$real_area] = [];
        }
        $this->_areas[$real_area][] = $virtual_area;
    }
    public function map_real_area_to_collection(string $real_area,array $virtual_areas): void
    {
        // we will not pass $virtual_area to map_real_area_to : to prevent check real_area key every once
        if (!isset($this->_areas[$real_area])){
            $this->_areas[$real_area] = [];
        }
        foreach ($virtual_areas as $key => $virtual_area){
            $this->_areas[$real_area][] = $virtual_area;
        }
    }
    public function real_area(string $virtual_area){
        foreach ($this->_areas as $real => $virtual_areas){
            foreach ($virtual_areas as $key => $virtual){
                if (trim($virtual_area) === trim($virtual)){
                    return $real;
                }
            }
        }
        return $this->_strictMode ? '' : $virtual_area;
    }

    public function virtual_areas(string $real_area){
        if (isset($this->_areas[$real_area])){
            return $this->_areas[$real_area];
        }
        return $this->_strictMode ? [] : $real_area;
    }
}
