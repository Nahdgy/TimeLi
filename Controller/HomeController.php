<?php

class HomeController
{
    public function index()
    {
      $modelPlaylists = new PlaylistsModel();
      $datas = $modelPlaylists->readAll();

       $playlists = [];

       if(count($datas) > 0)
       {
            foreach($datas as $data)
            {
                $playlists[] = new Playlist($data);
            }
       }
      include './View/home/index.php';
    }
}

?>