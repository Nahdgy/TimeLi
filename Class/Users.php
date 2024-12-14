<?php

class Users extends CoreClass
{
    private $use_id;
    private $use_firstname;
    private $use_lastname;
    private $use_email;
    private $use_pwd;
    private $use_statue;
    private $moo_id;
    private $rol_id;
    
    public function getId()
    {
        return $this->use_id;
    }

    public function getFirstname()
    {
        return $this->use_firstname;
    }

    public function getLastname()
    {
        return $this->use_lastname;
    }

    public function getEmail()
    {
        return $this->use_email;
    }

    public function getPwd()
    {
        return $this->use_pwd;
    }

    public function getStatue()
    {
        return $this->use_statue;
    }

    public function getMooId()
    {
        return $this->moo_id;
    }

    public function getRolId()
    {
        return $this->rol_id;
    }

    public function setId($use_id)
    {
        $this->use_id = $use_id;
    }

    public function setFirstname($use_firstname)
    {
        $this->use_firstname = $use_firstname;
    }

    public function setLastname($use_lastname)
    {
        $this->use_lastname = $use_lastname;
    }

    public function setEmail($use_email)
    {
        $this->use_email = $use_email;
    }   

    public function setPwd($use_pwd)
    {
        $this->use_pwd = $use_pwd;
    }

    public function setStatue($use_statue)
    {
        $this->use_statue = $use_statue;
    }

    public function setMooId($moo_id)
    {
        $this->moo_id = $moo_id;
    }

    public function setRolId($rol_id)
    {
        $this->rol_id = $rol_id;
    }
}
