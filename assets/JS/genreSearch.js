document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('genreSearch');
    const searchResults = document.getElementById('genreResults');
    const selectedGenresContainer = document.getElementById('selectedGenres');
    const genreCountElement = document.getElementById('genreCount');
    
    if (!searchInput || !searchResults || !selectedGenresContainer) {
        console.error('Elements non trouvés');
        return;
    }

    let timeoutId;
    let selectedGenres = new Set(); // Pour stocker les genres sélectionnés

    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        const query = this.value;

        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        timeoutId = setTimeout(() => {
            fetch(`index.php?ctrl=playlists&action=searchGenres&query=${encodeURIComponent(query)}`)
                .then(response => {
                    // Afficher les headers de la réponse
                    console.log('Headers:', Object.fromEntries(response.headers.entries()));
                    // Récupérer d'abord le texte brut
                    return response.text();
                })
                .then(text => {
                    // Afficher le texte brut reçu
                    console.log('Réponse brute:', text);
                    // Tenter de parser le JSON
                    const data = JSON.parse(text);
                    if (Array.isArray(data) && data.length > 0) {
                        searchResults.innerHTML = data.map(genre => `
                            <div class="p-2 search-result" data-id="${genre.id}" data-name="${genre.name}">
                                ${genre.name}
                            </div>
                        `).join('');
                        searchResults.style.display = 'block';
                    } else {
                        searchResults.innerHTML = '<div class="p-2">Aucun résultat trouvé</div>';
                        searchResults.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Erreur complète:', error);
                    searchResults.innerHTML = '<div class="p-2">Une erreur est survenue</div>';
                    searchResults.style.display = 'block';
                });
        }, 300);
    });

    function addGenreTag(id, name) {
        const tag = document.createElement('div');
        tag.className = 'badge bg-primary me-2 mb-2 p-2';
        tag.innerHTML = `
            ${name}
            <input type="hidden" name="genres[]" value="${id}">
            <span class="ms-2" style="cursor: pointer;" onclick="removeGenre(this, '${id}')">&times;</span>
        `;
        selectedGenresContainer.appendChild(tag);
        updateGenreCount();
    }

    // Fonction globale pour supprimer un genre
    window.removeGenre = function(element, id) {
        selectedGenres.delete(id);
        element.closest('.badge').remove();
        updateGenreCount();
    };

    // Cacher les résultats quand on clique ailleurs
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });

    searchResults.addEventListener('click', function(e) {
        const resultElement = e.target.closest('.search-result');
        if (!resultElement) return;

        const genreId = resultElement.dataset.id;
        const genreName = resultElement.dataset.name;

        // Vérifier si le genre n'est pas déjà sélectionné
        if (!selectedGenres.has(genreId)) {
            // Vérifier si on n'a pas déjà 10 genres
            if (selectedGenres.size >= 10) {
                alert('Vous ne pouvez pas sélectionner plus de 10 genres');
                return;
            }
            
            selectedGenres.add(genreId);
            addGenreTag(genreId, genreName);
        }

        // Nettoyer la recherche
        searchInput.value = '';
        searchResults.style.display = 'none';
    });

    function updateGenreCount() {
        genreCountElement.textContent = selectedGenres.size;
    }
});
