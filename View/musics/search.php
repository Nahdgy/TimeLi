<div class="search-results">
    <?php foreach ($musics as $music): ?>
        <div class="music-card">
            <h3><?= htmlspecialchars($music['title']) ?></h3>
            <p>Artiste: <?= htmlspecialchars($music['artist']) ?></p>
            <p>Album: <?= htmlspecialchars($music['album']) ?></p>
            <?php if ($music['preview_url']): ?>
                <audio controls>
                    <source src="<?= htmlspecialchars($music['preview_url']) ?>" type="audio/mpeg">
                </audio>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>