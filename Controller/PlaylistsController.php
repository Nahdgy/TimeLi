<?php

class PlaylistsController
{
    public function index()
    {
        $modelPlaylist = new PlaylistsModel();
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
        $modelPlaylist = new PlaylistsModel();
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
        if(isset($_SESSION['timeLi']['user']))
        {
            include './View/playlists/preferencies.php';
        }
        else
        {
            header('Location: index.php');
            exit;
        }
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

        $modelPlaylist = new PlaylistsModel();
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

    public function createPlaylist()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données du formulaire
            $genres = !empty($_POST['genres']) ? explode(',', $_POST['genres']) : [];
            
            $playlistData = [
                'mood' => $_POST['mood'] ?? null,
                'genres' => $genres, // Maintenant c'est un tableau
                'country' => $_POST['country'] ?? null,
                'travel_time' => $_POST['travel_time'] ?? null,
                'use_id' => $_SESSION['timeLi']['user']->getId()
            ];

            try {
                // Création de la playlist dans la BDD
                $modelPlaylist = new PlaylistsModel();
                $playlist_id = $modelPlaylist->create($playlistData);

                if ($playlist_id) {
                    // Récupération des musiques via Spotify
                    $spotify_config = require './Config/Spotify.php';
                    $spotifyHandler = new SpotifyApiHandler(
                        $spotify_config['client_id'],
                        $spotify_config['client_secret']
                    );

                    // Construction de la requête Spotify
                    $query = $this->buildSpotifyQuery($playlistData);
                    
                    // Récupération des musiques
                    $tracks = $spotifyHandler->searchTracks($query, $_SESSION['timeLi']['user']->getSpotifyAccessToken());
                    
                    // Ajout des musiques à la playlist
                    $this->addTracksToPlaylist($playlist_id, $tracks['tracks']['items']);

                    header('Location: index.php?ctrl=Playlists&action=show&id=' . $playlist_id);
                    exit;
                }
            } catch (Exception $e) {
                // Gérer l'erreur
                $_SESSION['error'] = $e->getMessage();
                header('Location: index.php?ctrl=Playlists&action=newPlaylist');
                exit;
            }
        }
    }

    private function buildSpotifyQuery($playlistData)
    {
        $query = [];

        // Ajout des paramètres de recherche basés sur l'humeur
        $moodQueries = [
            'happy' => 'valence:0.7-1.0 energy:0.7-1.0',
            'sad' => 'valence:0-0.3 energy:0-0.3',
            'party' => 'danceability:0.7-1.0 energy:0.8-1.0',
            'chill' => 'energy:0-0.3 acousticness:0.7-1.0',
            'angry' => 'energy:0.8-1.0 valence:0-0.3',
            'lover' => 'valence:0.5-0.8 acousticness:0.4-0.7'
        ];

        if (isset($playlistData['mood']) && isset($moodQueries[$playlistData['mood']])) {
            $query[] = $moodQueries[$playlistData['mood']];
        }

        // Ajout du marché (pays)
        if ($playlistData['country']) {
            $query[] = "market:{$playlistData['country']}";
        }

        // Ajout des genres
        if (!empty($playlistData['genres'])) {
            $query[] = 'genre:' . implode(',', $playlistData['genres']);
        }

        return implode(' ', $query);
    }

    private function addTracksToPlaylist($playlist_id, $tracks)
    {
        $modelPlaylist = new PlaylistsModel();
        $musicModel = new MusicModel();

        foreach ($tracks as $track) {
            // Création de la musique dans la BDD si elle n'existe pas
            $musicData = [
                'title' => $track['name'],
                'duration' => $track['duration_ms'],
                'spotify_id' => $track['id'],
                // Ajoutez d'autres champs nécessaires
            ];

            $music_id = $musicModel->create($musicData);

            // Ajout de la relation playlist-musique
            $modelPlaylist->addMusics([
                'playlist_id' => $playlist_id,
                'music_id' => $music_id
            ]);
        }
    }

    public function removeMusic()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['playlist_id'], $_POST['music_id'])) {
            $modelPlaylist = new PlaylistsModel();
            
            try {
                // Supprimer la musique de la playlist
                $modelPlaylist->removeMusic([
                    'playlist_id' => $_POST['playlist_id'],
                    'music_id' => $_POST['music_id']
                ]);
                
                // Mettre à jour la durée totale de la playlist
                $modelPlaylist->updateDuration($_POST['playlist_id']);
                
                header('Location: index.php?ctrl=Playlists&action=show&id=' . $_POST['playlist_id']);
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = "Erreur lors de la suppression de la musique";
                header('Location: index.php?ctrl=Playlists&action=show&id=' . $_POST['playlist_id']);
                exit;
            }
        }
    }
}
