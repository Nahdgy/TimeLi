<body>
    <h1>Inscription</h1>
    <form action="index.php?ctrl=Users&action=register" method="post">
        <div>
            <label for="firstname">Prénom :</label>
            <input type="text" id="firstname" name="firstname" required>
        </div>
        <div>
            <label for="lastname">Nom :</label>
            <input type="text" id="lastname" name="lastname" required>
        </div>
        <div>
            <label for="email">Adresse e-mail :</label>
            <input type="email" id="email" name="email" required>
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
    <p>Vous avez déjà un compte ? <a href="index.php?ctrl=Users&action=login&role=user">Connectez-vous ici</a></p>
</body>