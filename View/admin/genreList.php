<div class="container">
    <h1>Time Li</h1>
    <h3>Genres</h3>
    <div class="mb-4">
        <a href="?ctrl=admin&action=index&role=admin" class="btn btn-success">
            <i class="fas fa-solid fa-arrow-left"></i> Retour à l'accueil
        </a>
    </div>
    <!-- Bouton pour ajouter un nouveau genre -->
    <div class="mb-4">
        <button type="button" class="btn btn-info hover-purple" data-bs-toggle="modal" data-bs-target="#addGenreModal">
            <i class="fas fa-plus"></i> Ajouter un genre
        </button>
    </div>

    <!-- Liste des genres -->
    <div>
        <?php foreach($genres as $genre): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <p>Nom: <?= $genre->getLabel() ?></p>

                    <div class="btn-group">
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editGenreModal<?= $genre->getId() ?>">
                            <i class="fas fa-edit"></i> Modifier
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteGenreModal<?= $genre->getId() ?>">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal pour ajouter un nouveau genre -->
    <div class="modal fade" id="addGenreModal" tabindex="-1" aria-labelledby="addGenreModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGenreModalLabel">Ajouter un nouveau genre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="?ctrl=admin&action=addGenre" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="genreName" class="form-label">Nom du genre</label>
                            <input type="text" class="form-control" id="genreName" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="submit" class="btn btn-success">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal pour modifier un genre -->
    <?php foreach($genres as $genre): ?>
        <div class="modal fade" id="editGenreModal<?= $genre->getId() ?>" tabindex="-1" aria-labelledby="editGenreModalLabel<?= $genre->getId() ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editGenreModalLabel<?= $genre->getId() ?>">Modifier le genre</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <form action="?ctrl=admin&action=editGenre&id=<?= $genre->getId() ?>" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="genreName<?= $genre->getId() ?>" class="form-label">Nom du genre</label>
                                <input type="text" class="form-control" id="genreName<?= $genre->getId() ?>" name="name" value="<?= $genre->getLabel() ?>" required>
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
    <?php foreach($genres as $genre): ?>
        <div class="modal fade" id="deleteGenreModal<?= $genre->getId() ?>" tabindex="-1" aria-labelledby="deleteGenreModalLabel<?= $genre->getId() ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteGenreModalLabel<?= $genre->getId() ?>">Confirmation de suppression</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer ce genre ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <a href="?ctrl=admin&action=deleteGenre&id=<?= $genre->getId() ?>" class="btn btn-danger">Supprimer</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
