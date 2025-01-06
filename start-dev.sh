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

# Récupérer l'URL complète de ngrok
NGROK_URL=$(curl -s http://127.0.0.1:4040/api/tunnels | grep -o '"public_url":"[^"]*' | cut -d'"' -f4)

if [ -z "$NGROK_URL" ]; then
    echo "Erreur : Impossible de récupérer l'URL ngrok"
    exit 1
fi

# Mettre à jour la configuration avec le bon chemin
php Functions/update-ngrok.php

# Afficher les instructions avec l'URL complète
echo "==================================="
echo "URL à copier dans votre dashboard Spotify :"
echo "${NGROK_URL}/TimeLi/index.php?ctrl=Users&action=linkSpotify"
echo "==================================="
 