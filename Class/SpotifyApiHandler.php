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

            $ngrokConfig = new NgrokConfig();
            $ngrokUrl = $ngrokConfig->getCurrentUrl();

            $data = [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $ngrokUrl.'/TimeLi/index.php?ctrl=Users&action=linkSpotify '
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
        error_log('Début searchTracks avec query: ' . $query);
        error_log('Session actuelle: ' . print_r($_SESSION, true));
        
        if ($access_token === null) {
            $access_token = $this->access_token;
        }
        
        try {
            error_log('Tentative de recherche avec access_token: ' . substr($access_token, 0, 30) . '...');
            return $this->executeSearch($query, $access_token);
        } catch (Exception $e) {
            error_log('Exception attrapée dans searchTracks: ' . $e->getMessage());
            
            if (strpos($e->getMessage(), '401') !== false && isset($_SESSION['timeLi']['user'])) {
                try {
                    error_log('Token expiré, tentative de rafraîchissement');
                    $refresh_token = $_SESSION['timeLi']['user']->getSpotifyRefreshToken();
                    error_log('Refresh token récupéré: ' . ($refresh_token ? 'oui' : 'non'));
                    
                    if ($refresh_token) {
                        error_log('Tentative de rafraîchissement avec refresh_token: ' . substr($refresh_token, 0, 30) . '...');
                        $tokens = $this->refreshAccessToken($refresh_token);
                        error_log('Nouveaux tokens reçus: ' . print_r($tokens, true));
                        
                        // Mise à jour du token en base de données
                        $userModel = new UsersModel();
                        $success = $userModel->updateSpotifyCredentials(
                            $_SESSION['timeLi']['user']->getId(),
                            $_SESSION['timeLi']['user']->getSpotifyUserId(),
                            $tokens['access_token'],
                            $refresh_token
                        );
                        error_log('Mise à jour BDD: ' . ($success ? 'réussie' : 'échouée'));
                        
                        // Mise à jour du token en session
                        $_SESSION['timeLi']['user']->setSpotifyAccessToken($tokens['access_token']);
                        error_log('Session après mise à jour: ' . print_r($_SESSION, true));
                        
                        // Nouvel essai avec le nouveau token
                        error_log('Nouvelle tentative de recherche avec le nouveau token');
                        return $this->executeSearch($query, $tokens['access_token']);
                    } else {
                        error_log('Pas de refresh token disponible');
                    }
                } catch (Exception $refreshError) {
                    error_log('Erreur lors du rafraîchissement du token: ' . $refreshError->getMessage());
                    throw new Exception('Token d\'accès non disponible: ' . $refreshError->getMessage());
                }
            }
            throw $e;
        }
    }
    
    private function executeSearch($query, $access_token)
    {
        $url = "https://api.spotify.com/v1/search?q=" . urlencode($query) . "&type=track&limit=10";
        
        error_log('URL de recherche: ' . $url);
        error_log('Token utilisé: ' . substr($access_token, 0, 30) . '...');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $access_token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        error_log('Code HTTP reçu: ' . $http_code);
        error_log('Réponse brute: ' . substr($response, 0, 500) . '...');
        
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
    
    public function refreshAccessToken($refresh_token) 
    {
        $url = "https://accounts.spotify.com/api/token";
        
        $credentials = base64_encode($this->client_id . ':' . $this->client_secret);
        
        $headers = [
            'Authorization: Basic ' . $credentials,
            'Content-Type: application/x-www-form-urlencoded'
        ];
        
        $data = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Pour le développement local
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            throw new Exception('Erreur Curl: ' . curl_error($ch));
        }
        
        curl_close($ch);
        
        if ($http_code !== 200) {
            throw new Exception('Erreur lors du rafraîchissement du token: ' . $response);
        }
        
        $result = json_decode($response, true);
        
        if (!isset($result['access_token'])) {
            throw new Exception('Nouveau token non trouvé dans la réponse');
        }
        
        return $result;
    }
}