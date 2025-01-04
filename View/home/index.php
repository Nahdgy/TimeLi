<div class="d-flex">
    <!-- Barre latérale -->
    <div class="sidebar bg-dark text-white" style="width: 250px; min-height: 100vh;">
        <h1 class="p-3">TimeLi</h1>
        <!-- Vous pouvez ajouter ici d'autres éléments de navigation -->
         <form id="search-form" class="position-relative">
            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher une musique">
            <div id="searchResults" class="position-absolute w-100 bg-dark" style="z-index: 1000; display: none;"></div>
         </form>
    </div>

    <!-- Contenu principal -->
    <div class="main-content flex-grow-1 bg-secondary bg-opacity-25 p-4">
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
                        <img src="assets/img/no-playlist.jpg" alt="Pas de playlist" class="img-fluid w-25 my-3">
                    </div>
                <?php endif; ?>
            </ul>
            <div class="text-center">
                <a href="?ctrl=playlist&action=create" class="btn btn-primary rounded-circle">+</a>
            </div>
        </div>
    </div>
</div>
<script src="assets/JS/search.js"></script>

