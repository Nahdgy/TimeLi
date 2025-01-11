<div class="container">
    <h1>Time Li</h1>
    <h3>Musiques</h3>
    <div class="mb-4">
        <a href="?ctrl=admin&action=index&role=admin" class="btn btn-success">
            <i class="fas fa-solid fa-arrow-left"></i> Retour à l'accueil
        </a>
    </div>

    <!-- Liste des musiques -->
    <div>
        <?php foreach($musics as $music): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <p>Titre: <?= $music->getTitle() ?></p>
                    <p>Artiste: <?= $music->getArtist() ?></p>

                    <div class="btn-group">
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewMusicModal<?= $music->getId() ?>">
                            <i class="fas fa-eye"></i> Voir
                        </button>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editMusicModal<?= $music->getId() ?>">
                            <i class="fas fa-edit"></i> Modifier
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteMusicModal<?= $music->getId() ?>">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal pour voir les détails d'une musique -->
    <?php foreach($musics as $music): ?>
        <div class="modal fade" id="viewMusicModal<?= $music->getId() ?>" tabindex="-1" aria-labelledby="viewMusicModalLabel<?= $music->getId() ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewMusicModalLabel<?= $music->getId() ?>">Détails de la musique</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Titre:</strong> <?= $music->getTitle() ?></p>
                        <p><strong>Artiste:</strong> <?= $music->getArtist() ?></p>
                        <p><strong>Album:</strong> <?= $music->getAlbum() ?></p>
                        <p><strong>Année:</strong> <?= $music->getYear() ?></p>
                        <p><strong>Durée:</strong> <?= $music->getDuration() ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Modal pour modifier une musique -->
    <?php foreach($musics as $music): ?>
        <div class="modal fade" id="editMusicModal<?= $music->getId() ?>" tabindex="-1" aria-labelledby="editMusicModalLabel<?= $music->getId() ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMusicModalLabel<?= $music->getId() ?>">Modifier la musique</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <form action="?ctrl=admin&action=editMusic&id=<?= $music->getId() ?>" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="musicTitle<?= $music->getId() ?>" class="form-label">Titre</label>
                                <input type="text" class="form-control" id="musicTitle<?= $music->getId() ?>" name="title" value="<?= $music->getTitle() ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="musicArtist<?= $music->getId() ?>" class="form-label">Artiste</label>
                                <input type="text" class="form-control" id="musicArtist<?= $music->getId() ?>" name="artist" value="<?= $music->getArtist() ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="musicAlbum<?= $music->getId() ?>" class="form-label">Album</label>
                                <input type="text" class="form-control" id="musicAlbum<?= $music->getId() ?>" name="album" value="<?= $music->getAlbum() ?>">
                            </div>
                            <div class="mb-3">
                                <label for="musicYear<?= $music->getId() ?>" class="form-label">Année</label>
                                <input type="number" class="form-control" id="musicYear<?= $music->getId() ?>" name="year" value="<?= $music->getYear() ?>">
                            </div>
                            <div class="mb-3">
                                <label for="musicDuration<?= $music->getId() ?>" class="form-label">Durée</label>
                                <input type="text" class="form-control" id="musicDuration<?= $music->getId() ?>" name="duration" value="<?= $music->getDuration() ?>">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="submit" class="btn btn-warning">Modifier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Modal pour confirmation de la suppression -->
    <?php foreach($musics as $music): ?>
        <div class="modal fade" id="deleteMusicModal<?= $music->getId() ?>" tabindex="-1" aria-labelledby="deleteMusicModalLabel<?= $music->getId() ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteMusicModalLabel<?= $music->getId() ?>">Confirmation de suppression</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer cette musique ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <a href="?ctrl=admin&action=deleteMusic&id=<?= $music->getId() ?>" class="btn btn-danger">Supprimer</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
