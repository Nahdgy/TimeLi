document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('genreSearch');
    const searchResults = document.getElementById('genreResults');
    const selectedGenresContainer = document.getElementById('selectedGenres');
    const genreCountElement = document.getElementById('genreCount');
    let selectedGenres = new Set(); // Pour stocker les IDs des genres sélectionnés
    
    if (!searchInput || !searchResults || !selectedGenresContainer) {
        console.error('Elements non trouvés');
        return;
    }

    let timeoutId;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        const query = this.value;

        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        timeoutId = setTimeout(() => {
            fetch(`index.php?ctrl=playlists&action=searchGenres&query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
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
                    console.error('Erreur:', error);
                    searchResults.innerHTML = '<div class="p-2">Une erreur est survenue</div>';
                    searchResults.style.display = 'block';
                });
        }, 300);
    });

    // Fonction pour ajouter un tag de genre
    function addGenreTag(id, name) {
        const tag = document.createElement('div');
        tag.className = 'badge bg-primary me-2 mb-2 p-2';
        tag.innerHTML = `
            ${name}
            <span class="ms-2" style="cursor: pointer;" onclick="removeGenre('${id}')">&times;</span>
        `;
        selectedGenresContainer.appendChild(tag);
        updateGenreCount();
        updateSelectedGenresInput(Array.from(selectedGenres));
    }

    // Fonction globale pour supprimer un genre
    window.removeGenre = function(id) {
        selectedGenres.delete(id);
        const tags = selectedGenresContainer.children;
        for (let tag of tags) {
            if (tag.querySelector(`[onclick="removeGenre('${id}')"]`)) {
                tag.remove();
                break;
            }
        }
        updateGenreCount();
        updateSelectedGenresInput(Array.from(selectedGenres));
    };

    // Cacher les résultats quand on clique ailleurs
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });

    // Gestion de la sélection des genres
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

    // Fonction pour mettre à jour le compteur de genres
    function updateGenreCount() {
        genreCountElement.textContent = selectedGenres.size;
    }

    // Fonction pour mettre à jour le champ caché des genres
    function updateSelectedGenresInput(genres) {
        const genresString = Array.from(selectedGenres).join(',');
        document.getElementById('selectedGenresInput').value = genresString;
    }
});
