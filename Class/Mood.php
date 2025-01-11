<?php

class Mood extends CoreClass
{
    private $mood_id;
    private $mood_label;
    

//Getters
    public function getId()
    {
        return $this->mood_id;
    }

    public function getLabel()
    {
        return $this->mood_label;
    }

//Setters
    public function setId($id)
    {
        $this->mood_id = $id;
    }

    public function setLabel($label)
    {
        $this->mood_label = $label;
    }
}