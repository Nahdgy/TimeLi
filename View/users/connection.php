
<body class="bg-body-secondary min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="container">
        <div class="card shadow-lg mx-auto" style="max-width: 400px;">
            <div class="card-body p-4">
                <h1 class="card-title text-center mb-4"><?= $_GET['role']=='user'? 'Connexion': 'Connexion Admin'?></h1>

                <?php if(isset($_GET['login']) && $_GET['login'] === 'error') : ?>
                    <div class="alert alert-danger" role="alert">
                        <strong>Erreur :</strong> Identifiant ou mot de passe incorrect
                    </div>
                <?php endif; ?>

                <form action="" method="post">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email/Pseudo" required>
                        <label for="email"><?= $_GET['role']=='user'? 'Email/Pseudo': 'Identifiant'?></label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Mot de passe" required>
                        <label for="pwd">Mot de passe</label>
                    </div>

                    <input type="hidden" name="submit" value="">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">Se connecter</button>
                </form>

                <?php if(isset($_SESSION['timeLi']['user']) && !$_SESSION['timeLi']['user']->getSpotifyUserId()): ?>
                    <?php
                    try {
                        $spotify_config = @require './Config/Spotify.php';
                        
                        if ($spotify_config === false || !is_array($spotify_config)) {
                            throw new Exception('Erreur de chargement de la configuration Spotify');
                        }

                        if (!isset($spotify_config['client_id']) || !isset($spotify_config['client_secret'])) {
                            throw new Exception('Configuration Spotify incomplète');
                        }

                        $scopes = 'user-read-private user-read-email user-library-read playlist-read-private';
                        $redirect_uri = 'http://localhost/TimeLi.com/index.php?ctrl=Users&action=linkSpotify';
                        
                        $params = [
                            'client_id' => $spotify_config['client_id'],
                            'response_type' => 'code',
                            'redirect_uri' => $redirect_uri,
                            'scope' => $scopes,
                            'show_dialog' => 'true'
                        ];
                        
                        $auth_url = 'https://accounts.spotify.com/authorize?' . 
                                    http_build_query($params, '', '&', PHP_QUERY_RFC3986);
                        ?>
                        <div class="text-center mt-3">
                            <a href="<?php echo htmlspecialchars($auth_url); ?>" class="btn btn-success">
                                <i class="fab fa-spotify me-2"></i>Connecter avec Spotify
                            </a>
                        </div>
                    <?php 
                    } catch (Exception $e) {
                        error_log('Erreur Spotify: ' . $e->getMessage());
                        ?>
                        <div class="alert alert-danger">
                            Une erreur est survenue lors de la configuration Spotify.
                        </div>
                    <?php
                    }
                    ?>
                <?php endif; ?>

                <?php if(isset($_GET['role']) && $_GET['role'] === 'user') : ?>
                <p class="text-center mt-4 mb-0">
                    Nouveau sur TimeLI ? 
                    <a href="index.php?ctrl=Users&action=register" class="text-decoration-none">Créer un compte</a>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>