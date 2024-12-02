<?php
class SpotifyApiHandler {
    private $client_id;
    private $client_secret;
    private $access_token;
    
    public function __construct($client_id, $client_secret) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->getAccessToken();
    }
    
    private function getAccessToken() 
    {
        // Logique pour obtenir le token d'accès
        $url = "https://accounts.spotify.com/api/token";
        $data = "grant_type=client_credentials&client_id=" . $this->client_id . "&client_secret=" . $this->client_secret;
        $headers = "Content-Type: application/x-www-form-urlencoded";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    }
    
    public function searchTracks($query) 
    {
        // Recherche de musiques via l'API
        $url = "https://api.spotify.com/v1/search?q=" . urlencode($query) . "&type=track&limit=50";
        $headers = "Authorization: Bearer " . $this->access_token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->access_token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

}