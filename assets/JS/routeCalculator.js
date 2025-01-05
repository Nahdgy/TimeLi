let map;
let routingControl;

function initMap() {
    const mapElement = document.getElementById('map');
    if (!mapElement) {
        console.warn('Élément map non trouvé');
        return;
    }

    // Initialisation de la carte centrée sur la France
    map = L.map('map').setView([46.603354, 1.888334], 6);

    // Ajout de la couche OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Initialisation du géocodeur
    const geocoder = L.Control.geocoder({
        defaultMarkGeocode: false
    }).addTo(map);

    // Initialisation des champs d'autocomplétion
    const originInput = document.getElementById('origin');
    const destinationInput = document.getElementById('destination');

    // Écouteurs d'événements pour la recherche d'adresses
    originInput.addEventListener('change', () => searchAddress(originInput.value, 'origin'));
    destinationInput.addEventListener('change', () => searchAddress(destinationInput.value, 'destination'));

    // Ajout de l'autocomplétion pour les champs d'adresse
    setupAutocomplete('origin');
    setupAutocomplete('destination');
}

function searchAddress(address, type) {
    if (!address) return;

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                const location = [parseFloat(data[0].lat), parseFloat(data[0].lon)];
                
                if (type === 'origin') {
                    window.originCoords = location;
                } else {
                    window.destinationCoords = location;
                }

                if (window.originCoords && window.destinationCoords) {
                    calculateRoute();
                }
            }
        });
}

function calculateRoute() {
    if (!window.originCoords || !window.destinationCoords) return;

    // Si un itinéraire existe déjà, le supprimer
    if (routingControl) {
        map.removeControl(routingControl);
    }

    // Création du nouvel itinéraire
    routingControl = L.Routing.control({
        waypoints: [
            L.latLng(window.originCoords[0], window.originCoords[1]),
            L.latLng(window.destinationCoords[0], window.destinationCoords[1])
        ],
        router: L.Routing.osrmv1({
            serviceUrl: 'https://router.project-osrm.org/route/v1'
        }),
        lineOptions: {
            styles: [{color: '#3388ff', weight: 6}]
        }
    }).addTo(map);

    // Mise à jour du temps de trajet
    routingControl.on('routesfound', function(e) {
        const routes = e.routes;
        const durationInMinutes = Math.round(routes[0].summary.totalTime / 60);
        
        document.getElementById('duration').innerHTML = 
            `Temps de trajet estimé : ${durationInMinutes} minutes`;
        document.getElementById('travel_time').value = durationInMinutes;
    });
}

function setupAutocomplete(inputId) {
    const input = document.getElementById(inputId);
    const resultsDiv = document.createElement('div');
    resultsDiv.className = 'address-results';
    resultsDiv.style.cssText = 'display: none; position: absolute; z-index: 1000; background: white; width: 100%; max-height: 200px; overflow-y: auto; border: 1px solid #ccc; border-radius: 4px;';
    input.parentNode.style.position = 'relative';
    input.parentNode.appendChild(resultsDiv);

    let timeoutId;

    input.addEventListener('input', function() {
        clearTimeout(timeoutId);
        const query = this.value;

        if (query.length < 3) {
            resultsDiv.style.display = 'none';
            return;
        }

        timeoutId = setTimeout(() => {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5`)
                .then(response => response.json())
                .then(data => {
                    resultsDiv.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(place => {
                            const div = document.createElement('div');
                            div.className = 'address-item';
                            div.style.cssText = 'padding: 8px; cursor: pointer; border-bottom: 1px solid #eee;';
                            div.innerHTML = place.display_name;
                            div.addEventListener('mouseenter', () => {
                                div.style.backgroundColor = '#f0f0f0';
                            });
                            div.addEventListener('mouseleave', () => {
                                div.style.backgroundColor = 'white';
                            });
                            div.addEventListener('click', () => {
                                input.value = place.display_name;
                                resultsDiv.style.display = 'none';
                                if (inputId === 'origin') {
                                    window.originCoords = [parseFloat(place.lat), parseFloat(place.lon)];
                                } else {
                                    window.destinationCoords = [parseFloat(place.lat), parseFloat(place.lon)];
                                }
                                if (window.originCoords && window.destinationCoords) {
                                    calculateRoute();
                                }
                            });
                            resultsDiv.appendChild(div);
                        });
                        resultsDiv.style.display = 'block';
                    } else {
                        resultsDiv.style.display = 'none';
                    }
                });
        }, 300);
    });

    // Cacher les résultats quand on clique en dehors
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !resultsDiv.contains(e.target)) {
            resultsDiv.style.display = 'none';
        }
    });
}

// Initialisation de la carte au chargement
document.addEventListener('DOMContentLoaded', function() {
    const mapElement = document.getElementById('map');
    if (mapElement) {
        initMap();
    }
}); 