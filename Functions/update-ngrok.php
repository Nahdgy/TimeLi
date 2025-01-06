<?php
require_once __DIR__ . '/../Config/NgrokConfig.php';

$ngrokConfig = new NgrokConfig();
$newUrl = $ngrokConfig->updateNgrokUrl();

if ($newUrl) {
    echo "URL Ngrok mise à jour : $newUrl\n";
    echo "URLs de redirection mises à jour dans SpotifyApiHandler.php\n";
} else {
    echo "Erreur lors de la mise à jour de l'URL Ngrok\n";
}