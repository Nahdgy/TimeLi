<?php

class Genre extends CoreClass
{
    private $genre_id;
    private $genre_label;

//Getters
    public function getId()
    {
        return $this->genre_id;
    }   

    public function getLabel()
    {
        return $this->genre_label;
    }   

//Setters
    public function setId($id)
    {
        $this->genre_id = $id;
    }      

    public function setLabel($label)
    {
        $this->genre_label = $label;
    }
}