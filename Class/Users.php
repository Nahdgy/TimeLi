<?php

class Users extends CoreClass
{
    private $use_id;
    private $use_name;
    private $use_login;
    private $use_pwd;
    private $use_statue;
    
    public function getId()
    {
        return $this->use_id;
    }

    public function getName()
    {
        return $this->use_name;
    }

    public function getLogin()
    {
        return $this->use_login;
    }

    public function getPwd()
    {
        return $this->use_pwd;
    }

    public function getStatue()
    {
        return $this->use_statue;
    }

    public function setId($use_id)
    {
        $this->use_id = $use_id;
    }

    public function setName($use_name)
    {
        $this->use_name = $use_name;
    }

    public function setLogin($use_login)
    {
        $this->use_login = $use_login;
    }   

    public function setPwd($use_pwd)
    {
        $this->use_pwd = $use_pwd;
    }

    public function setStatue($use_statue)
    {
        $this->use_statue = $use_statue;
    }
}
