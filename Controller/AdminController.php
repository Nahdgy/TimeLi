<?php

class AdminController
{
    public function index()
    {
        $modelUsers = new UsersModel();
        $datas = $modelUsers->readAll();
        $users = [];
        if(count($datas) > 0)
        {
            foreach($datas as $data)
            {
                $users[] = new Users($data);
            }
        }
        include './View/admin/index.php';
    }
}

