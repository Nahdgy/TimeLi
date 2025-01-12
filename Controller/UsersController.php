<?php
require_once './Config/NgrokConfig.php';

class UsersController
{
    private $spotifyApiHandler;

    public function __construct()
    {
        $spotify_config = require_once './Config/Spotify.php';
        $this->spotifyApiHandler = new SpotifyApiHandler($spotify_config['client_id'], $spotify_config['client_secret']);
    }

    public function landing()
    {
        include './View/landing/index.php';
    }
    public function index()
    {
        
        if(empty($_GET['action']))
        {
            include './View/users/index.php';
        }
        
    }
    public function login()
    {
        if(isset($_POST['submit']))
        {
            $modelUsers = new UsersModel();
            $data = $modelUsers->findByEmail($_POST['email']); 
            
            if($data)
            { 
                
                $user = new Users($data);

                $email = $_POST['email'];
                $pwd = $_POST['pwd'];

                

                if($_GET['role'] === 'user')
                {
                   
                    if($email === $user->getEmail() && password_verify($pwd, $user->getPwd()))
                    {
                        echo 'connecté';
                        header('Location: index.php?ctrl=home&action=index&role=user');
                        return $_SESSION['timeLi']['user'] = $user;
                    }
                    else
                    {
                        header('Location: index.php?ctrl=Users&action=login&role=admin&login=error');
                    }
            
                } 
            }
            else if ($data === false)
            {
                header('Location: index.php?ctrl=Users&action=login&role=admin&login=error');
            }
        }
        include './View/users/connection.php';
    }
    public function register()
    {
        $modelUsers = new UsersModel();
        if(isset($_POST['submit'])&& $modelUsers->verifyIfEmailExists($_POST['email']) === false)
        {
            $pwd = $_POST['pwd'];
            $confirmPwd = $_POST['confirmPwd'];
            if($pwd === $confirmPwd)
            {
                $pwd = password_hash($pwd, PASSWORD_DEFAULT);
            }
            else
            {
                header('Location: index.php?ctrl=Users&action=register&pwd=error');
            }

            $modelUsers = new UsersModel();
            $id = $modelUsers->create($pwd);

            if($id)
            {
                header('Location: index.php?ctrl=Users&action=login&role=user');
            }
            else
            {
                header('Location: index.php?ctrl=Users&action=register&id=error');
            }
            
        }
        include './View/users/registration.php';
    }
    public function logout()
    {
        session_destroy();
        header('Location: index.php?ctrl=users&action=login&role=user');
    }
    public function delete()
    {
        if(isset($_GET['id']) && isset($_SESSION['timeLi']['user']) && $_SESSION['timeLi']['user']->getId() == $_GET['id'] && isset($_POST['submit']))
        {
            $modelUsers = new UsersModel();
            $modelUsers->delete($_GET['id']);
            $_SESSION['timeLi']['message'] = "Votre compte a bien été supprimé";
            $this->logout();
        }
        else
        {
            header('Location: index.php?ctrl=home&action=index');
        }
        
    }
    public function update()
    {
        if(isset($_POST['submit']))
        {
            $model = new UsersModel();
            $model->update($_GET['id']);
            header('Location: index.php?ctrl=users&action=index');
 
        }
        else
        {
            header('Location: index.php');
        }
    }
    public function linkSpotify()
    {
        if (!isset($_SESSION['timeLi']['user'])) {
            header('Location: index.php?ctrl=Users&action=login&role=user');
            exit;
        }

        try {
            $spotify_config = require './Config/Spotify.php';
            
            // Vérifier que les configurations sont présentes
            if (empty($spotify_config['client_id']) || empty($spotify_config['client_secret'])) {
                throw new Exception('Configuration Spotify manquante');
            }

            // Si nous n'avons pas encore de code d'autorisation
            if (!isset($_GET['code'])) {
                $scopes = 'user-read-private user-read-email user-library-read playlist-read-private';
                
                // Récupérer l'URL ngrok depuis le fichier de configuration
                $ngrokConfig = new NgrokConfig();
                $ngrokUrl = $ngrokConfig->getCurrentUrl();
                if (!$ngrokUrl) {
                    throw new Exception('URL ngrok non configurée');
                }
                
                $redirect_uri = $ngrokUrl . '/TimeLi/index.php?ctrl=Users&action=linkSpotify';
                
                // Construction de l'URL avec tous les paramètres requis
                $params = [
                    'client_id' => $spotify_config['client_id'],
                    'response_type' => 'code',
                    'redirect_uri' => $redirect_uri,
                    'scope' => $scopes,
                    'show_dialog' => 'true'
                ];
                
                $auth_url = 'https://accounts.spotify.com/authorize?' . http_build_query($params);
                
                header('Location: ' . $auth_url);
                exit;
            }

            // Le reste du code pour gérer le retour de Spotify...
            $spotifyHandler = new SpotifyApiHandler($spotify_config['client_id'], $spotify_config['client_secret']);
            
            // Échange du code contre des tokens
            $tokens = $this->spotifyApiHandler->getAccessToken($_GET['code']);
            
            if (!$tokens) {
                throw new Exception('Échec de la récupération des tokens');
            }
            
            // Récupération des informations de l'utilisateur Spotify
            $spotify_user = $this->spotifyApiHandler->getCurrentUser($tokens['access_token']);
            
            if (!$spotify_user) {
                throw new Exception('Échec de la récupération du profil utilisateur');
            }
            
            // Mise à jour de la base de données
            $model = new UsersModel();
            $success = $model->updateSpotifyCredentials(
                $_SESSION['timeLi']['user']->getId(),
                $spotify_user['id'],
                $tokens['access_token'],
                $tokens['refresh_token']
            );
            
            if (!$success) {
                throw new Exception('Échec de la mise à jour des informations Spotify');
            }
            
            // Mise à jour de la session
            $_SESSION['timeLi']['user']->setSpotifyUserId($spotify_user['id']);
            $_SESSION['timeLi']['user']->setSpotifyAccessToken($tokens['access_token']);
            $_SESSION['timeLi']['user']->setSpotifyRefreshToken($tokens['refresh_token']);
            
            header('Location: index.php?ctrl=home&action=index&spotify=success');
            exit;
            
        } catch (Exception $e) {
            error_log('Erreur Spotify: ' . $e->getMessage());
            header('Location: index.php?ctrl=home&action=index&error=' . urlencode($e->getMessage()));
            exit;
        }
    }

    private function validateUserData($data) {
        $errors = [];
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email invalide";
        }
        
        if (strlen($data['password']) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
        }
        
        return $errors;
    }

    public function getSpotifyAuthUrl() {
        try {
            $spotify_config = require './Config/Spotify.php';
            $scopes = 'user-read-private user-read-email user-library-read playlist-read-private';
            
            // Récupérer l'URL ngrok
            $ngrokConfig = new NgrokConfig();
            $ngrokUrl = $ngrokConfig->getCurrentUrl();
            $redirect_uri = $ngrokUrl . '/TimeLi/index.php?ctrl=Users&action=linkSpotify';
            
            $params = [
                'client_id' => $spotify_config['client_id'],
                'response_type' => 'code',
                'redirect_uri' => $redirect_uri,
                'scope' => $scopes,
                'show_dialog' => 'true'
            ];
            
            return 'https://accounts.spotify.com/authorize?' . http_build_query($params);
        } catch (Exception $e) {
            error_log('Erreur génération URL Spotify: ' . $e->getMessage());
            return false;
        }
    }
}

?>