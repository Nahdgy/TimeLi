<body class="bg-purple min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="container">
        <div class="card shadow-lg mx-auto" style="max-width: 400px;">
            <div class="card-body p-4">
                <h1 class="card-title text-center mb-4">Inscription</h1>
                
                <form action="index.php?ctrl=Users&action=register" method="post">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Nom" required>
                        <label for="lastname">Nom</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Prénom" required>
                        <label for="firstname">Prénom</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        <label for="email">Email</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Mot de passe" required>
                        <label for="pwd">Mot de passe</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="confirmPwd" name="confirmPwd" placeholder="Confirmer le mot de passe" required>
                        <label for="confirmPwd">Confirmer le mot de passe</label>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary w-100 rounded-pill py-2">S'inscrire</button>
                </form>

                <p class="text-center mt-4 mb-0">
                    Vous avez déjà un compte ? 
                    <a href="index.php?ctrl=Users&action=login&role=user" class="text-decoration-none">Connectez-vous ici</a>
                </p>
            </div>
        </div>
    </div>
</body>