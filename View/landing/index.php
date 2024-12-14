<div id="carouselExemple" class="carousel slide" data-bs-ride="carousel">
    <!-- Indicateurs -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExemple" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExemple" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExemple" data-bs-slide-to="2" aria-label="Slide 3"></button>
        <button type="button" data-bs-target="#carouselExemple" data-bs-slide-to="3" aria-label="Slide 4"></button>
        <button type="button" data-bs-target="#carouselExemple" data-bs-slide-to="4" aria-label="Slide 5"></button>
    </div>

    <!-- Slides -->
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="d-flex align-items-center justify-content-center min-vh-100 bg-purple text-white text-center p-5">
                <p class="fs-4">Bienvenue sur TimeLI, ta plateforme qui te permet de créer ta playlist parfaite</p>
            </div>
        </div>

        <div class="carousel-item">
            <div class="d-flex align-items-center justify-content-center min-vh-100 bg-purple text-white text-center p-5">
                <div>
                    <div class="fs-1 mb-3">🤗😂😭😅😊😌</div>
                    <p class="fs-4">Choisis ton mood du moment.</p>
                </div>
            </div>
        </div>

        <div class="carousel-item">
            <div class="d-flex align-items-center justify-content-center min-vh-100 bg-purple text-white text-center p-5">
                <div>
                    <div class="fs-1 mb-3">🎸🎤🎷🎼🎛️🎮</div>
                    <p class="fs-4">Choisis ton genre de musique</p>
                </div>
            </div>
        </div>

        <div class="carousel-item">
            <div class="d-flex align-items-center justify-content-center min-vh-100 bg-purple text-white text-center p-5">
                <div>
                    <div class="fs-1 mb-3">🗺️</div>
                    <p class="fs-4">Choisis ton pays de référence musicale</p>
                </div>
            </div>
        </div>

        <div class="carousel-item">
            <div class="d-flex align-items-center justify-content-center min-vh-100 bg-purple text-white text-center p-5">
                <div>
                    <div class="fs-1 mb-3">🏃⌚📍‍➡️</div>
                    <p class="fs-4">Et enfin choisis ton itinéraire ou la durée de ta playlist</p>
                    <a href="index.php?ctrl=Users&action=login&role=user" class="btn btn-primary fs-4">Let's go !</a>
                </div>
                <div class="admin-login-container">
                    <a href="index.php?ctrl=Users&action=login&role=admin" class="btn btn-secondary">Connexion Admin</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Contrôles -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExemple" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Précédent</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExemple" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Suivant</span>
    </button>
</div>

<style>
    .bg-purple {
        background-color: #6f42c1;
    }
    
    .carousel-item {
        transition: transform .6s ease-in-out;
    }
    
    .carousel-indicators [data-bs-target] {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    .admin-login-container {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1000;
    }
</style>
