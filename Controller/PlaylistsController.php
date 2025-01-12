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
                'id' => $genre['gen_id'],
                'name' => $genre['gen_label']
            ];
        }, $genres);
        
        // S'assurer qu'il n'y a aucune sortie après le JSON
        echo json_encode($formattedGenres);
        exit;
    }

    public function createPlaylist()
    {
        if(isset($_SESSION['timeLi']['user']))
        {
            if(isset($_POST['submit']))
            {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') 
                {

                    try 
                    {
                        // Récupération des données du formulaire
                        $genres = !empty($_POST['genres']) ? explode(',', $_POST['genres']) : [];
                    
                        $playlistData = [
                            'title' => htmlspecialchars($_POST['title']),
                            'duration' => intval($_POST['travel_time']),
                            'visibility' => 'private',
                            'use_id' => $_SESSION['timeLi']['user']->getId(),
                            'moo_id' => $this->getMoodId($_POST['mood']),
                            'gen_id' => $genres[0] ?? null,
                            'cou_id' => $this->getCountryId($_POST['country'])
                        ];

                        error_log('PlaylistData: ' . print_r($playlistData, true));

                    
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
                    } 
                    catch (Exception $e) 
                    {
                        // Gérer l'erreur
                        error_log('Erreur lors de la création de la playlist: ' . $e->getMessage());
                        $_SESSION['error'] = $e->getMessage();
                        header('Location: index.php?ctrl=Playlists&action=createPlaylist');
                        exit;
                    }
                }
            }
        }
        else
        {
            $_SESSION['error'] = "Vous devez vous connecter pour créer une playlist";
            header('Location: index.php?ctrl=Users&action=login&role=user&login=error');
        }
        include './View/playlists/addPlaylist.php';
    }

#Méthodes de conversion des données poussées par l'utilisateur en requête SQL
    private function getMoodId($mood) 
    {
        $moodMap = [
            'sad' => 1,
            'happy' => 2,
            'lover' => 3,
            'angry' => 4,
            'chill' => 5,
            'party' => 6,
            'none' => 7,

        ];
        return $moodMap[$mood] ?? 7; // Retourne 1 par défaut si l'humeur n'est pas trouvée
    }
    private function getCountryId($countryCode)
    {
        $modelPlaylist = new PlaylistsModel();
        $countries = $modelPlaylist->readAllCountries();
        foreach ($countries as $country) {
                if ($country['code'] === $countryCode) {
                    return $country['Id'];
                }
            }
            return 1;
    }
#Méthode de conversion des données poussées par l'utilisateur en requête Spotify
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
        
        // Récupérer le temps de trajet en minutes depuis la playlist
        $playlistData = $modelPlaylist->readOne($playlist_id)[0];
        $marginInMs = 5 * 60 * 1000;
        $targetDuration = ($playlistData['travel_time'] * 60 * 1000) + $marginInMs;
        $currentDuration = 0;

        foreach ($tracks as $track) {
            // Vérifier si l'ajout de cette musique dépasserait le temps de trajet + marge (5 minutes)
            if ($currentDuration + $track['duration_ms'] > $targetDuration) {
                break;
            }

            // Création de la musique dans la BDD si elle n'existe pas
            $musicData = [
                'title' => $track['name'],
                'release' => $track['album']['release_date'],
                'duration' => $track['duration_ms'],
                'rating' => $track['popularity'],
                'link' => $track['external_urls']['spotify'],
                'album_id' => $track['album']['id'],
                'artist_id' => $track['artists'][0]['id'],
                'spotify_id' => $track['id']
            ];

            $music_id = $musicModel->create($musicData);

            // Ajout de la relation playlist-musique
            $modelPlaylist->addMusics([
                'playlist_id' => $playlist_id,
                'music_id' => $music_id
            ]);

            // Mettre à jour la durée totale
            $currentDuration += $track['duration_ms'];
        }

        // Mettre à jour la durée totale de la playlist
        $modelPlaylist->updateDuration($playlist_id);
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
