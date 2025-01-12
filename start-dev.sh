#!/bin/bash

# Définir le chemin vers ngrok dans le projet
NGROK_PATH="./ngrok/ngrok.exe" 

# Démarrer ngrok en arrière-plan avec le chemin complet
"$NGROK_PATH" http 80 &

# Attendre que ngrok soit prêt
sleep 5

# Vérifier si ngrok est bien démarré
if ! curl -s http://127.0.0.1:4040/api/tunnels > /dev/null; then
    echo "Erreur : ngrok n'est pas démarré correctement"
    echo "Veuillez vérifier que ngrok est bien installé et accessible"
    exit 1
fi

# Récupérer l'URL complète de ngrok et la formater correctement
NGROK_URL=$(curl -s http://127.0.0.1:4040/api/tunnels | grep -o '"public_url":"[^"]*' | cut -d'"' -f4)
REDIRECT_URI="${NGROK_URL}/TimeLi/index.php?ctrl=Users&action=linkSpotify"

# Mettre à jour la configuration ngrok
echo "{\"url\": \"$NGROK_URL\"}" > Config/ngrok-url.json

# Mettre à jour la configuration Spotify
cat > Config/Spotify.php << EOF
<?php
return [
    'client_id' => '763d23d8e7f4422f9ff98dbab39c07f1',
    'client_secret' => 'df419bfa1f7f43edb49f55d1e0260a92',
    'redirect_uri' => '$REDIRECT_URI',
    'scopes' => 'user-read-private user-read-email playlist-modify-public playlist-modify-private'
];
EOF

# Afficher les instructions avec l'URL complète
echo "==================================="
echo "URL de redirection Spotify à configurer :"
echo "$REDIRECT_URI"
echo "==================================="
 