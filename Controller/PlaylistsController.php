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
        include './View/playlists/preferencies.php';
    }

    public function searchGenres() 
    {
        // Arrêter toute sortie précédente
        ob_clean();
        
        // Définir l'en-tête JSON
        header('Content-Type: application/json');
        
        if (!isset($_GET['query'])) {
            echo json_encode(['error' => 'Requête manquante']);
            exit;
        }

        $modelPlaylist = new PlaylistModel();
        $genres = $modelPlaylist->searchGenres($_GET['query']);
        
        // Transformer les résultats pour correspondre au format attendu par le JS
        $formattedGenres = array_map(function($genre) {
            return [
                'id' => $genre['typ_id'],
                'name' => $genre['typ_label']
            ];
        }, $genres);
        
        // S'assurer qu'il n'y a aucune sortie après le JSON
        echo json_encode($formattedGenres);
        exit;
    }
}
