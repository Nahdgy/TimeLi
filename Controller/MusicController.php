<?php

class MusicController
{
    private $spotifyApiHandler;
    private $musicModel;

    public function __construct()
    {
        $this->spotifyApiHandler = new SpotifyApiHandler(
            $_ENV['SPOTIFY_CLIENT_ID'],
            $_ENV['SPOTIFY_CLIENT_SECRET']
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
    
}
