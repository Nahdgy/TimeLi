<?php

class HomeController
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
      include './View/home/index.php';
    }
}

?>