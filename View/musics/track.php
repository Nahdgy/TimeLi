
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card bg-dark text-white">
                <?php if ($track['image']): ?>
                    <img src="<?= htmlspecialchars($track['image']) ?>" class="card-img-top" alt="Album cover">
                <?php endif; ?>
                
                <div class="card-body">
                    <h1 class="card-title"><?= htmlspecialchars($track['title']) ?></h1>
                    <h2 class="card-subtitle mb-3"><?= htmlspecialchars($track['artist']) ?></h2>
                    <p class="card-text">Album: <?= htmlspecialchars($track['album']) ?></p>
                    
                    <?php if ($track['preview_url']): ?>
                        <audio controls class="w-100 mb-3">
                            <source src="<?= htmlspecialchars($track['preview_url']) ?>" type="audio/mpeg">
                            Votre navigateur ne supporte pas l'élément audio.
                        </audio>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?= htmlspecialchars($track['spotify_url']) ?>" 
                           class="btn btn-success" 
                           target="_blank">
                            Écouter sur Spotify
                        </a>
                        <a href="index.php?ctrl=home&action=index" 
                           class="btn btn-secondary">
                            Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>