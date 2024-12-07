<?php

class PlaylistsController
{
    public function index()
    {
        $modelPlaylist = new PlaylistModel();
        $datas = $modelPlaylist->readAll();

        $playlists = [];

        if(count($datas) > 0)
        {
            foreach($datas as $data)
            {
                $playlists[] = new Playlist($data);
            }
        }

        include './View/playlists/index.php';
    }
    public function show()
    {
        $modelPlaylist = new PlaylistModel();
        $datas = $modelPlaylist->readOne($_GET['id']);

        $playlist = [];

        if(count($datas) > 0)
        {
            foreach($datas as $data)
            {
                $playlist[] = new Playlist($data);
            }
        }

        include './View/playlists/show.php';
    }
    public function newPlaylist()
    {
        if(isset($_POST['submit']))
        {
            $modelPlaylist = new PlaylistModel();
            $modelPlaylist->create($_POST);
        }
    }
}
