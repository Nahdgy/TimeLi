let map;
let autocompleteOrigin;
let autocompleteDestination;
let directionsService;
let directionsRenderer;

function initMap() 
{
    // Initialisation de la carte
    map = new google.maps.Map(document.getElementById("map"), 
    {
        center: { lat: 46.603354, lng: 1.888334 }, // Centre de la France
        zoom: 6,
    });

    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);

    // Initialisation des champs d'autocomplétion
    autocompleteOrigin = new google.maps.places.Autocomplete(
        document.getElementById("origin"),
        { types: ["address"] }
    );

    autocompleteDestination = new google.maps.places.Autocomplete(
        document.getElementById("destination"),
        { types: ["address"] }
    );

    // Écouteurs d'événements pour le calcul d'itinéraire
    document.getElementById("origin").addEventListener("change", calculateRoute);
    document.getElementById("destination").addEventListener("change", calculateRoute);
}

function calculateRoute() 
{
    const origin = document.getElementById("origin").value;
    const destination = document.getElementById("destination").value;

    if (origin && destination) 
        {
        directionsService.route(
            {
                origin: origin,
                destination: destination,
                travelMode: google.maps.TravelMode.DRIVING,
            },
            (response, status) => {
                if (status === "OK") 
                {
                    directionsRenderer.setDirections(response);
                    
                    // Récupération du temps de trajet en minutes
                    const durationInMinutes = Math.round(
                        response.routes[0].legs[0].duration.value / 60
                    );
                    
                    // Affichage du temps de trajet
                    document.getElementById("duration").innerHTML = 
                        `Temps de trajet estimé : ${durationInMinutes} minutes`;
                    
                    // Stockage dans le champ caché
                    document.getElementById("travel_time").value = durationInMinutes;
                } 
                else 
                {
                    window.alert("Impossible de calculer l'itinéraire : " + status);
                }
            }
        );
    }
} 