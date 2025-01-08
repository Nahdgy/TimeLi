<div class="container mt-4">
    <?php if (!empty($playlist)): ?>
        <?php $playlistInfo = $playlist[0]; ?>
        
        <div class="card bg-dark text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="card-title"><?= htmlspecialchars($playlistInfo->getTitle()) ?></h1>
                    <div>
                        <span class="badge bg-primary">
                            Durée totale: <?= gmdate("H:i:s", $playlistInfo->getDuration()) ?>
                        </span>
                        <span class="badge bg-secondary">
                            Créée le <?= date('d/m/Y', strtotime($playlistInfo->getCreation())) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Durée</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($playlist as $music): ?>
                        <tr>
                            <td><?= htmlspecialchars($music->getMusTitle()) ?></td>
                            <td><?= gmdate("i:s", $music->getMusDuration() / 1000) ?></td>
                            <td>
                                <form action="index.php?ctrl=Playlists&action=removeMusic" method="POST" class="d-inline">
                                    <input type="hidden" name="playlist_id" value="<?= $playlistInfo->getId() ?>">
                                    <input type="hidden" name="music_id" value="<?= $music->getMusId() ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette musique ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            Aucune musique n'a été trouvée dans cette playlist.
        </div>
    <?php endif; ?>
</div>
