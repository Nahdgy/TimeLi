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
        // Désactiver tout output automatique
        ob_clean();
        
        // Définir l'en-tête JSON avant toute sortie
        header('Content-Type: application/json');

        try {
            if (!isset($_GET['query'])) {
                echo json_encode(['error' => 'Requête manquante']);
                exit;
            }

            $query = $_GET['query'];
            $results = $this->musicModel->search($query);

            // Si pas de résultats locaux, chercher sur Spotify
            if (empty($results)) {
                $spotifyResults = $this->spotifyApiHandler->searchTracks($query);
                
                if (!empty($spotifyResults['tracks']['items'])) {
                    $tracks = [];
                    foreach ($spotifyResults['tracks']['items'] as $track) {
                        $tracks[] = [
                            'mus_id' => $track['id'],
                            'mus_title' => $track['name'],
                            'aut_name' => $track['artists'][0]['name'],
                            'alb_title' => $track['album']['name']
                        ];
                    }
                    echo json_encode($tracks);
                    exit;
                }
            }

            echo json_encode($results);
            exit;

        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
}
