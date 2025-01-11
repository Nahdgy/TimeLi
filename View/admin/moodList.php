<div class="container">
    <h1>Time Li</h1>
    <h3>Humeurs</h3>
    <div class="mb-4">
        <a href="?ctrl=admin&action=index&role=admin" class="btn btn-success">
            <i class="fas fa-solid fa-arrow-left"></i> Retour à l'accueil
        </a>
    </div>
    <!-- Bouton pour ajouter une nouvelle humeur -->
    <div class="mb-4 ">
        <button type="button" class="btn btn-info hover-purple" data-bs-toggle="modal" data-bs-target="#addMoodModal">
            <i class="fas fa-plus"></i> Ajouter une humeur
        </button>
    </div>

    <!-- Liste des humeurs -->
    <div>
        <?php foreach($moods as $mood): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <p>Nom: <?= $mood->getLabel() ?></p>

                    <div class="btn-group">
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editMoodModal<?= $mood->getId() ?>">
                            <i class="fas fa-edit"></i> Modifier
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteMoodModal<?= $mood->getId() ?>">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal pour ajouter une nouvelle humeur -->
    <div class="modal fade" id="addMoodModal" tabindex="-1" aria-labelledby="addMoodModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMoodModalLabel">Ajouter une nouvelle humeur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="?ctrl=admin&action=addMood" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="moodName" class="form-label">Nom de l'humeur</label>
                            <input type="text" class="form-control" id="moodName" name="name" required>
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

    <!-- Modal pour modifier une humeur -->
    <?php foreach($moods as $mood): ?>
        <div class="modal fade" id="editMoodModal<?= $mood->getId() ?>" tabindex="-1" aria-labelledby="editMoodModalLabel<?= $mood->getId() ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMoodModalLabel<?= $mood->getId() ?>">Modifier l'humeur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <form action="?ctrl=admin&action=editMood&id=<?= $mood->getId() ?>" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="moodName<?= $mood->getId() ?>" class="form-label">Nom de l'humeur</label>
                                <input type="text" class="form-control" id="moodName<?= $mood->getId() ?>" name="name" value="<?= $mood->getLabel() ?>" required>
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
    <?php foreach($moods as $mood): ?>
        <div class="modal fade" id="deleteMoodModal<?= $mood->getId() ?>" tabindex="-1" aria-labelledby="deleteMoodModalLabel<?= $mood->getId() ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteMoodModalLabel<?= $mood->getId() ?>">Confirmation de suppression</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer cette humeur ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <a href="?ctrl=admin&action=deleteMood&id=<?= $mood->getId() ?>" class="btn btn-danger">Supprimer</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
