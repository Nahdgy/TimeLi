<h1>TimeLi</h1>
<h2>Bienvenue <?= $_SESSION['timeLi']['user']->getName()?></h2>
<h3>Dernières playlists</h3>
<div>
    <ul class="d-flex justify-content-center flex-wrap">
        <?php if(!empty($playlists)): ?>
            <?php foreach($playlists as $playlist): ?>
                <li class="card m-2" style="width: 18rem;">
                    <div class="text-center">
                        <img src="#" class="card-img-top w-75 mx-auto" alt="Playlist 1">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $playlist['Name'] ?></h5>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>Vous n'avez pas encore créé de playlist</li>
            <div class="text-center">
                <img src="assets/img/no-playlist.jpg" alt="Pas de playlist" class="img-fluid w-25 my-3">
            </div>
        <?php endif; ?>
    </ul>
    <div class="text-center">
        <a href="index.php?route=addPlaylist" class="btn btn-primary">+</a>
    </div>
</div>

