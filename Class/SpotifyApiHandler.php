<?php
class SpotifyApiHandler {
    private $client_id;
    private $client_secret;
    private $access_token;
    
    public function __construct($client_id, $client_secret) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }
    
    public function getAccessToken($code = null) 
    {
        $url = "https://accounts.spotify.com/api/token";
        
        // Préparer les données d'authentification
        $credentials = base64_encode($this->client_id . ':' . $this->client_secret);
        
        $headers = [
            'Authorization: Basic ' . $credentials,
            'Content-Type: application/x-www-form-urlencoded'
        ];
        
        // Si un code est fourni, on demande un token d'accès utilisateur
        if ($code) {
            $data = [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => 'https://2abb-2001-861-5d90-f9f0-8884-f6d8-a0f4-25e3.ngrok-free.app/TimeLi/index.php?ctrl=Users&action=linkSpotify'
            ];
        } else {
            // Sinon, on demande un token d'accès client 
            $data = [
                'grant_type' => 'client_credentials'
            ];
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Pour le développement local uniquement
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            throw new Exception('Erreur Curl: ' . curl_error($ch));
        }
        
        curl_close($ch);
        
        if ($http_code !== 200) {
            throw new Exception('Erreur lors de l\'authentification Spotify: ' . $response);
        }
        
        $result = json_decode($response, true);
        
        if (!$result) {
            throw new Exception('Erreur de décodage de la réponse JSON');
        }
        
        if ($code) {
            // Pour l'authentification utilisateur, on retourne tous les tokens
            return $result;
        } else {
            // Pour l'authentification client, on stocke juste le access_token
            if (isset($result['access_token'])) {
                $this->access_token = $result['access_token'];
                return $result['access_token'];
            } else {
                throw new Exception('Token d\'accès non trouvé dans la réponse');
            }
        }
    }
    
    public function getCurrentUser($access_token) 
    {
        $url = "https://api.spotify.com/v1/me";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $access_token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($http_code !== 200) {
            throw new Exception('Erreur lors de la récupération du profil Spotify: ' . $response);
        }
        
        curl_close($ch);
        
        return json_decode($response, true);
    }
    
    public function ensureValidToken() {
        if (!$this->access_token) {
            // Si pas de token, on en demande un nouveau
            $this->getAccessToken();
        }
        
        try {
            // Test si le token est valide
            $this->searchTracks('test');
        } catch (Exception $e) {
            if (strpos($e->getMessage(), '401') !== false) {
                // Token expiré, on en demande un nouveau
                $this->getAccessToken();
            } else {
                throw $e;
            }
        }
    }
    
    public function searchTracks($query, $access_token = null) 
    {
        if ($access_token === null) {
            $access_token = $this->access_token;
        }
        
        if (empty($access_token)) {
            throw new Exception('Token d\'accès non disponible');
        }
        
        $url = "https://api.spotify.com/v1/search?q=" . urlencode($query) . "&type=track&limit=10";
        
        // Debug log
        error_log('URL de recherche: ' . $url);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $access_token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Pour le développement local
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        // Debug log
        error_log('Code HTTP: ' . $http_code);
        error_log('Réponse brute: ' . $response);
        
        if (curl_errno($ch)) {
            error_log('Erreur CURL: ' . curl_error($ch));
        }
        
        curl_close($ch);
        
        if ($http_code === 401) {
            throw new Exception('Token expiré');
        }
        
        if ($http_code !== 200) {
            throw new Exception('Erreur lors de la recherche Spotify: ' . $response);
        }
        
        $result = json_decode($response, true);
        if ($result === null) {
            error_log('Erreur JSON: ' . json_last_error_msg());
            throw new Exception('Réponse JSON invalide de Spotify');
        }
        
        return $result;
    }
    
    public function getTrack($track_id, $access_token)
    {
        if (empty($access_token)) {
            throw new Exception('Token d\'accès non disponible');
        }
        
        $url = "https://api.spotify.com/v1/tracks/" . $track_id;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $access_token
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        
        if ($http_code !== 200) {
            throw new Exception('Erreur lors de la récupération de la piste');
        }
        
        return json_decode($response, true);
    }
    
    private function encryptToken($token) {
        $key = getenv('ENCRYPTION_KEY');
        $cipher = "aes-256-gcm";
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        return openssl_encrypt($token, $cipher, $key, 0, $iv);
    }
}