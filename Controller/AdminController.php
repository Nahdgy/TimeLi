<?php

class AdminController
{
    public function index()
    {
        $modelUsers = new UsersModel();
        $datasUsers = $modelUsers->readAll();
        $count = $modelUsers->countPlaylists($_GET['id']);
        $users = [];

        if(count($datasUsers) > 0)
        {
            foreach($datasUsers as $data)
            {
                $users[] = new Users($data);
            }
        }

        $datasPlaylists = $modelUsers->readPlaylists($_GET['id']);
        $playlists = [];
        if(count($datasPlaylists) > 0)
        {
            foreach($datasPlaylists as $data)
            {
                $playlists[] = new Playlist($data);
            }
        }
       
        include './View/admin/index.php';
    }

    public function showPlaylists()
    {
        $modelUsers = new UsersModel();
        $datas = $modelUsers->readPlaylists($_GET['id']);
        $playlists = [];
        if(count($datas) > 0)
        {
            foreach($datas as $data)
            {
                $playlists[] = new Playlist($data);
            }
        }
        include './View/admin/showPlaylists.php';
    }

    public function view()
    {
        $modelUsers = new UsersModel();
        $datas = $modelUsers->readOne($_GET['id']);
        $user = new Users($datas);
        include './View/admin/view.php';
    }

    public function edit()
    {
        $modelUsers = new UsersModel();
        $datas = $modelUsers->update($_GET['id']);
        $user = new Users($datas);
        include './View/admin/edit.php';
    }

    public function delete()
    {
        $modelUsers = new UsersModel();
        $modelUsers->delete($_GET['id']);
        header('Location: index.php?ctrl=admin&action=index');
    }
}

