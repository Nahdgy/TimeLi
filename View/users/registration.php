<body>
    <h1>Inscription</h1>
    <form action="index.php?ctrl=Users&action=register" method="post">
        <div>
            <label for="name">Prénom :</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div>
            <label for="login">Login :</label>
            <input type="text" id="login" name="login" required>
        </div>
        <div>
            <label for="pwd">Mot de passe :</label>
            <input type="password" id="pwd" name="pwd" required>
        </div>
        <div>
            <label for="confirmPwd">Confirmer le mot de passe :</label>
            <input type="password" id="confirmPwd" name="confirmPwd" required>
        </div>
        <button type="submit" name="submit">S'inscrire</button>
    </form>
    <p>Vous avez déjà un compte ? <a href="index.php?ctrl=Users&action=login&state=student">Connectez-vous ici</a></p>
</body>