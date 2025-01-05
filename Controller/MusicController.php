<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

class MusicController
{
    private $spotifyApiHandler;
    private $musicModel;

    public function __construct()
    {
        $spotify_config = require_once './Config/Spotify.php';
        
        $this->spotifyApiHandler = new SpotifyApiHandler(
            $spotify_config['client_id'],
            $spotify_config['client_secret']
        );
        
        $this->musicModel = new MusicModel();
    }
    public function importFromSpotify() {
        try {
            // Récupération des musiques via l'API
            $tracks = $this->spotifyApiHandler->searchTracks('votre_recherche');
            
            foreach ($tracks as $track) {
                $musicData = [
                    'title' => $track->name,
                    'artist' => $track->artists[0]->name,
                    'album' => $track->album->name,
                    'duration' => $track->duration_ms,
                    'preview_url' => $track->preview_url,
                    'spotify_id' => $track->id
                ];
                
                $this->musicModel->create($musicData);
            }
            
            return ['success' => true, 'message' => 'Importation réussie'];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function search($query)
    {
        try 
        {
            //Recherche locale
            $localResults = $this->musicModel->search($query);

            if(empty($localResults)) 
            {
                //Si la BDD est vide l'on recherche via spotify
                $spotifyResults = $this->spotifyApiHandler->searchTracks($query);

                //On enregistre les résultats dans la BDD
                foreach ($spotifyResults as $track) 
                {
                    $musicData = [
                        'title' => $track->name,
                        'artist' => $track->artists[0]->name,
                        'album' => $track->album->name,
                        'duration' => $track->duration_ms,
                        'preview_url' => $track->preview_url,
                        'spotify_id' => $track->id
                    ];
                    $this->musicModel->create($musicData);
                }
                    return $localResults;
            }
            include './View/musics/search.php';
        } 
        catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    public function index()
    {
        
    }
    
    public function searchAjax()
    {
        // Désactiver l'affichage des erreurs PHP
        ini_set('display_errors', 0);
        error_reporting(0);
        
        // S'assurer qu'aucun output n'a été envoyé avant
        if (headers_sent($filename, $linenum)) {
            error_log("Headers already sent in $filename on line $linenum");
            echo json_encode(['error' => 'Internal server error']);
            exit;
        }

        // Nettoyer tout buffer de sortie existant
        ob_clean();
        
        // Définir les headers
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, must-revalidate');
        
        try {
            if (!isset($_GET['query'])) {
                throw new Exception('Requête invalide');
            }

            $query = $_GET['query'];
            
            if (!isset($_SESSION['timeLi']['user']) || !$_SESSION['timeLi']['user']->getSpotifyAccessToken()) {
                throw new Exception('Token d\'accès non disponible');
            }

            $spotify_config = require './Config/Spotify.php';
            $spotifyApiHandler = new SpotifyApiHandler($spotify_config['client_id'], $spotify_config['client_secret']);
            
            $results = $spotifyApiHandler->searchTracks($query, $_SESSION['timeLi']['user']->getSpotifyAccessToken());
            
            if (!empty($results['tracks']['items'])) {
                $tracks = array_map(function($track) {
                    return [
                        'mus_id' => $track['id'],
                        'mus_title' => htmlspecialchars($track['name']),
                        'aut_name' => htmlspecialchars($track['artists'][0]['name'])
                    ];
                }, $results['tracks']['items']);
                
                echo json_encode($tracks, JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([]);
            }
            
        } catch (Exception $e) {
            error_log('Erreur de recherche Spotify: ' . $e->getMessage());
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    public function track()
    {
        if (!isset($_GET['id'])) {
            header('Location: index.php?ctrl=home&action=index');
            exit;
        }

        try {
            $spotify_config = require './Config/Spotify.php';
            $spotifyApiHandler = new SpotifyApiHandler($spotify_config['client_id'], $spotify_config['client_secret']);
            
            // Récupérer les détails de la piste
            $track_id = $_GET['id'];
            $track_details = $spotifyApiHandler->getTrack($track_id, $_SESSION['timeLi']['user']->getSpotifyAccessToken());
            
            // Passer les données à la vue
            $track = [
                'id' => $track_details['id'],
                'title' => $track_details['name'],
                'artist' => $track_details['artists'][0]['name'],
                'album' => $track_details['album']['name'],
                'image' => $track_details['album']['images'][0]['url'] ?? null,
                'preview_url' => $track_details['preview_url'],
                'spotify_url' => $track_details['external_urls']['spotify']
            ];
            
            include './View/musics/track.php';
            
        } catch (Exception $e) {
            header('Location: index.php?ctrl=home&action=index&error=' . urlencode($e->getMessage()));
            exit;
        }
    }
}
