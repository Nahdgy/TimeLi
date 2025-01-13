document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    
    if (!searchInput || !searchResults) return;

    let timeoutId;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        const query = this.value.trim();

        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        timeoutId = setTimeout(() => {
            fetch(`index.php?ctrl=music&action=searchAjax&query=${encodeURIComponent(query)}`)
            .then(response => {
                if (!response.ok) {
                    if (response.status === 401) 
                    {
                        return fetch('index.php?ctrl=Users&action=refreshToken')
                            .then(refreshResponse => {
                                if (!refreshResponse.ok) {
                                    throw new Error('Erreur lors du rafraîchissement du token');
                                }
                                return refreshResponse.json();
                            })
                            .then(refreshData => {
                                if (refreshData.success) {
                                    return fetch(`index.php?ctrl=music&action=searchAjax&query=${encodeURIComponent(query)}`);
                                } else {
                                    throw new Error('Impossible de rafraîchir le token');
                                }
                            });
                    }
                    throw new Error(`HTTP error! status: ${response.status}`);
                    }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    if (data.error === 'Token d\'accès non disponible') {
                        window.location.href = 'index.php?ctrl=Users&action=linkSpotify';
                        return;
                    }
                    throw new Error(data.error);
                }

                searchResults.innerHTML = '';
                
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(track => {
                        const div = document.createElement('div');
                        div.className = 'search-item';
                        div.innerHTML = `
                            <a href="index.php?ctrl=music&action=track&id=${track.mus_id}" 
                               class="d-block p-2 text-white text-decoration-none border-bottom hover-bg-secondary">
                                ${track.mus_title} - ${track.aut_name}
                            </a>
                        `;
                        searchResults.appendChild(div);
                    });
                } else {
                    const div = document.createElement('div');
                    div.className = 'p-2 text-white';
                    div.textContent = 'Aucun résultat trouvé';
                    searchResults.appendChild(div);
                }
                
                searchResults.style.display = 'block';
            })
            .catch(error => {
                console.error('Erreur:', error);
                searchResults.innerHTML = `
                    <div class="p-2 text-white">
                        Une erreur est survenue lors de la recherche
                    </div>
                `;
                searchResults.style.display = 'block';
            });
        }, 300);
    });

    // Fermer les résultats quand on clique ailleurs
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
});