document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined') {
        $('.your-class').slick();
    }

    // Gestion des cartes d'émotions
    const moodInputs = document.querySelectorAll('input[name="mood"]');
    const moodCards = document.querySelectorAll('.mood-card');

    moodInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Retirer la bordure de toutes les cartes
            moodCards.forEach(card => {
                card.style.border = 'none';
            });

            // Ajouter la bordure à la carte sélectionnée
            if (this.checked) {
                const selectedCard = this.closest('.mood-card');
                selectedCard.style.border = '2px solid #613A91';
            }
        });
    });
});

function setCookieConsent() {
    if (!localStorage.getItem('cookieConsent')) {
        const banner = document.createElement('div');
        banner.innerHTML = `
            <div class="cookie-banner">
                <p>Nous utilisons des cookies pour améliorer votre expérience.</p>
                <button onclick="acceptCookies()">Accepter</button>
                <button onclick="refuseCookies()">Refuser</button>
            </div>
        `;
        document.body.appendChild(banner);
    }
}