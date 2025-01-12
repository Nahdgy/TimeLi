<?php
class NgrokConfig {
    private $configFile;
    private $spotifyConfigFile;
    private $filesToUpdate = [
        'Class/SpotifyApiHandler.php' => [
            'pattern' => "/'redirect_uri' => '(.*?)'/",
            'path' => '/TimeLi/index.php?ctrl=Users&action=linkSpotify'
        ],
        'Controller/UsersController.php' => [
            'pattern' => "/'redirect_uri' => '(.*?)'/",
            'path' => '/TimeLi/index.php?ctrl=Users&action=linkSpotify'
        ]
    ];
    
    public function __construct() {
        $this->configFile = __DIR__ . '/ngrok-url.json';
        $this->spotifyConfigFile = __DIR__ . '/Spotify.php';
    }
    
    public function updateNgrokUrl() {
        try {
            echo "Tentative de récupération de l'URL ngrok...\n";
            
            // Vérifier si le fichier de config existe
            echo "Chemin du fichier de config : " . $this->configFile . "\n";
            
            $ngrokData = json_decode(file_get_contents('http://127.0.0.1:4040/api/tunnels'), true);
            echo "Données ngrok reçues : " . print_r($ngrokData, true) . "\n";
            
            if (!isset($ngrokData['tunnels'][0]['public_url'])) {
                throw new Exception("Impossible de récupérer l'URL ngrok");
            }
            
            $ngrokUrl = $ngrokData['tunnels'][0]['public_url'];
            
            // Sauvegarder l'URL
            file_put_contents($this->configFile, json_encode(['url' => $ngrokUrl]));
            
            // Mettre à jour les URLs dans tous les fichiers concernés
            $this->updateRedirectUris($ngrokUrl);
            
            return $ngrokUrl;
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    private function updateRedirectUris($baseUrl) {
        foreach ($this->filesToUpdate as $filePath => $config) {
            $fullPath = __DIR__ . '/../' . $filePath;
            if (!file_exists($fullPath)) {
                error_log("Le fichier $fullPath n'existe pas");
                continue;
            }

            $content = file_get_contents($fullPath);
            $fullUrl = $baseUrl . $config['path'];
            
            $newContent = str_replace(
                "'redirect_uri' => 'https://",
                "'redirect_uri' => '" . $baseUrl . $config['path'],
                $content
            );

            if ($newContent !== $content) {
                file_put_contents($fullPath, $newContent);
                error_log("URL mise à jour dans $filePath");
            } else {
                error_log("Aucun changement nécessaire dans $filePath");
            }
        }
    }
    
    public function getCurrentUrl() {
        if (file_exists($this->configFile)) {
            $data = json_decode(file_get_contents($this->configFile), true);
            return $data['url'] ?? null;
        }
        return null;
    }
}
