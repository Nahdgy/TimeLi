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
        $url = "https://accounts.spotify.com/api/token";
        
        // Préparer les données d'authentification
        $credentials = base64_encode($this->client_id . ':' . $this->client_secret);
        
        $headers = [
            'Authorization: Basic ' . $credentials,
            'Content-Type: application/x-www-form-urlencoded'
        ];
        
        $data = http_build_query([
            'grant_type' => 'client_credentials'
        ]);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($http_code !== 200) {
            throw new Exception('Erreur lors de l\'authentification Spotify: ' . $response);
        }
        
        curl_close($ch);
        
        $result = json_decode($response, true);
        if (isset($result['access_token'])) {
            $this->access_token = $result['access_token'];
        } else {
            throw new Exception('Token d\'accès non trouvé dans la réponse');
        }
    }
    
    public function searchTracks($query) 
    {
        if (empty($this->access_token)) {
            throw new Exception('Token d\'accès non disponible');
        }
        
        $url = "https://api.spotify.com/v1/search?q=" . urlencode($query) . "&type=track&limit=10";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->access_token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($http_code !== 200) {
            throw new Exception('Erreur lors de la recherche Spotify: ' . $response);
        }
        
        curl_close($ch);
        
        return json_decode($response, true);
    }
}