<div class="container">
    <h1>Time Li</h1>
    <h3>Utilisateurs</h3>
    <a href="?ctrl=admin&action=index&role=admin" class="btn btn-success">
        <i class="fas fa-plus"></i> Retour à l'accueil
    </a>
        <!-- Afficher les utilisateurs -->
        <div>
            <?php foreach($users as $user): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <p>Prénom: <?= $user->getFirstname() ?></p>

                        <div class="btn-group">
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#userModal<?= $user->getId() ?>">
                                <i class="fas fa-eye"></i> Voir
                            </button>
                            <a href="?ctrl=admin&action=edit&id=<?= $user->getId() ?>" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $user->getId() ?>">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Modal voir les détails d'un utilisateur -->
        <div>
        
            <?php foreach($users as $user): ?>
                <div class="modal fade" id="userModal<?= $user->getId() ?>" tabindex="-1" aria-labelledby="userModalLabel<?= $user->getId() ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content bg-dark">
                            <div class="modal-header">
                                <h5 class="modal-title" id="userModalLabel<?= $user->getId() ?>">Détails de l'utilisateur</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body">
                                <div class="user-details">
                                    <h6>Informations personnelles</h6>
                                    <p><strong>Prénom:</strong> <?= $user->getFirstname() ?></p>
                                    <p><strong>Nom:</strong> <?= $user->getLastname() ?></p>
                                    <p><strong>Email:</strong> <?= $user->getEmail() ?></p>
                                    <p><strong>Statut:</strong> <?= $user->getStatue() == 1? "En ligne": "Hors Ligne" ?></p>
                                </div>
                                <div class="user-playlists mt-4">
                                    <h6>Playlists</h6>
                                    <?php if(!empty($playlists)): ?>
                                        <ul class="list-group">
                                            <?php foreach($playlists as $playlist): ?>
                                                <li class="list-group-item">
                                                    <?= $playlist['Name'] ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p class="">Aucune playlist trouvée</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Modal pour confirmation de la suppression d'un utilisateur -->
        <?php foreach($users as $user): ?>
        <div class="modal fade" id="deleteModal<?= $user->getId() ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $user->getId() ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel<?= $user->getId() ?>">Confirmation de suppression</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer cet utilisateur ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <a href="?ctrl=admin&action=delete&id=<?= $user->getId() ?>" class="btn btn-danger">Supprimer</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
</div>