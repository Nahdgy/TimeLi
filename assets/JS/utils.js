/**Verifie si l'element existe et ajoute un event listener */
document.addEventListener('DOMContentLoaded', function() {
    const element = document.getElementById('votre-element');
    if (element) {
        element.addEventListener('onChanged', function() {
            // votre code
        });
    }
}); 