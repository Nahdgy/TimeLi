<body>
<h1>Connexion</h1>
<?php if(isset($_GET['login']) && $_GET['login'] === 'error') : ?>
        <div class="alert alert-danger" role="alert">
            <strong>Erreur :</strong> Identifiant ou mot de passe incorrect
        </div>
<?php endif; ?>
    <form action="" method="post">
        <div>
            <label for="email">Adresse e-mail :</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="pwd">Mot de passe :</label>
            <input type="password" id="pwd" name="pwd" required>
        </div>
        <input type="hidden" name="submit" value="">
        <button type="submit">Se connecter</button>
        <?php debug($users); ?>
        <input type="hidden" name="id" value="<?= $users->getId(); ?>">
    </form>
    <p>Vous n'avez pas de compte ? <a href="index.php?ctrl=Users&action=register">Inscrivez-vous ici</a></p>
    
</body>