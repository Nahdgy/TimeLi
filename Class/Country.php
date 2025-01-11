<?php

class Country extends CoreClass
{
    private $country_id;
    private $country_code;
    private $country_label;

//Getters
    public function getId()
    {
        return $this->country_id;
    }

    public function getCode()
    {
        return $this->country_code;
    }

    public function getLabel()
    {
        return $this->country_label;
    }

//Setters
    public function setId($id)
    {
        $this->country_id = $id;
    }   

    public function setCode($code)
    {
        $this->country_code = $code;
    }   

    public function setLabel($label)
    {
        $this->country_label = $label;
    }
}