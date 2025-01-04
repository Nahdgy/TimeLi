document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    
    if (!searchInput || !searchResults) {
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
            fetch(`index.php?ctrl=music&action=searchAjax&query=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Données reçues:', data);
                
                if (data.error) {
                    searchResults.innerHTML = `<div class="p-2 text-white">Erreur: ${data.error}</div>`;
                    searchResults.style.display = 'block';
                    return;
                }

                if (Array.isArray(data) && data.length > 0) {
                    searchResults.innerHTML = data.map(music => `
                        <div class="p-2 text-white border-bottom search-item" 
                             data-id="${music.mus_id || ''}"
                             style="cursor: pointer;">
                            ${music.mus_title} - ${music.aut_name}
                        </div>
                    `).join('');
                } else {
                    searchResults.innerHTML = '<div class="p-2 text-white">Aucun résultat trouvé</div>';
                }
                searchResults.style.display = 'block';
            })
            .catch(error => {
                console.error('Erreur:', error);
                searchResults.innerHTML = '<div class="p-2 text-white">Une erreur est survenue</div>';
                searchResults.style.display = 'block';
            });
        }, 300);
    });

    // Cacher les résultats quand on clique ailleurs
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
});