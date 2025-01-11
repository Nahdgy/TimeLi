<div class="container">
    <h1>Time Li</h1>
    <h3>Pays</h3>
    <div class="mb-4">
        <a href="?ctrl=admin&action=index&role=admin" class="btn btn-success">
            <i class="fas fa-solid fa-arrow-left"></i> Retour à l'accueil
        </a>
    </div>
    <!-- Bouton pour ajouter un nouveau pays -->
    <div class="mb-4">
        <button type="button" class="btn btn-info hover-purple" data-bs-toggle="modal" data-bs-target="#addCountryModal">
            <i class="fas fa-plus"></i> Ajouter un pays
        </button>
    </div>

    <!-- Liste des pays -->
    <div>
        <?php foreach($countries as $country): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <p>Nom: <?= $country->getLabel() ?></p>

                    <div class="btn-group">
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editCountryModal<?= $country->getId() ?>">
                            <i class="fas fa-edit"></i> Modifier
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCountryModal<?= $country->getId() ?>">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal pour ajouter un nouveau pays -->
    <div class="modal fade" id="addCountryModal" tabindex="-1" aria-labelledby="addCountryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCountryModalLabel">Ajouter un nouveau pays</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="?ctrl=admin&action=addCountry" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="countryName" class="form-label">Nom du pays</label>
                            <input type="text" class="form-control" id="countryName" name="name" required>
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

    <!-- Modal pour modifier un pays -->
    <?php foreach($countries as $country): ?>
        <div class="modal fade" id="editCountryModal<?= $country->getId() ?>" tabindex="-1" aria-labelledby="editCountryModalLabel<?= $country->getId() ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCountryModalLabel<?= $country->getId() ?>">Modifier le pays</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <form action="?ctrl=admin&action=editCountry&id=<?= $country->getId() ?>" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="countryName<?= $country->getId() ?>" class="form-label">Nom du pays</label>
                                <input type="text" class="form-control" id="countryName<?= $country->getId() ?>" name="name" value="<?= $country->getLabel() ?>" required>
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
    <?php foreach($countries as $country): ?>
        <div class="modal fade" id="deleteCountryModal<?= $country->getId() ?>" tabindex="-1" aria-labelledby="deleteCountryModalLabel<?= $country->getId() ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteCountryModalLabel<?= $country->getId() ?>">Confirmation de suppression</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer ce pays ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <a href="?ctrl=admin&action=deleteCountry&id=<?= $country->getId() ?>" class="btn btn-danger">Supprimer</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
