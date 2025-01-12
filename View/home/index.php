<div class="d-flex">
    <!-- Barre d'en-tête -->
    <div class="header bg-dark text-white w-100 d-flex justify-content-between align-items-center h-100 px-4">
        <h1>TimeLi</h1>
        <form id="search-form" class="position-relative w-25">
                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher une musique">
                <div id="searchResults" class="position-absolute w-100 bg-dark" style="z-index: 1000; display: none;"></div>
            </form>
        </div>
    </div>

    <!-- Boutton de déconnexion -->
    <div class="text-end">
        <a href="?ctrl=users&action=logout" class="btn btn-danger mt-3">Déconnexion</a>
    </div>

    <!-- Contenu principal -->
    <div class="main-content flex-grow-1 bg-secondary bg-opacity-25 p-4">
        <?php debug($_SESSION); ?>
        <h2 class="text-white">Bienvenue <?= $_SESSION['timeLi']['user']->getFirstname()?></h2>
        <h3 class="text-white">Dernières playlists</h3>
        
        <div>
            <ul class="d-flex justify-content-start flex-wrap list-unstyled">
                <?php if(!empty($playlists)): ?>
                    <?php foreach($playlists as $playlist): ?>
                        <li class="card m-2 bg-dark text-white" style="width: 18rem;">
                            <div class="text-center">
                                <img src="#" class="card-img-top w-75 mx-auto" alt="Playlist 1">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $playlist['Name'] ?></h5>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="text-white">Vous n'avez pas encore créé de playlist</li>
                    <div class="text-center">
                        <img src="assets/img/no-playlist.png" alt="Pas de playlist" class="img-fluid w-25 my-3">
                    </div>
                <?php endif; ?>
            </ul>
            <div class="text-center">
                <a href="?ctrl=playlists&action=createPlaylist" class="btn btn-primary rounded-circle">+</a>
            </div>
        </div>
    </div>
</div>
<script src="assets/JS/musicSearch.js"></script>

