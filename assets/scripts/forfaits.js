document.addEventListener('page:loaded', function() {
    const showLotsBtn = document.querySelector('.show-lots');
    const retour = document.querySelector('.retour');
    const forfaitsContainer = document.getElementById('forfaits-container');
    const lotsContainer = document.getElementById('lots-container');
    const lotButtons = document.querySelectorAll('.lot-button'); // Sélectionne uniquement les boutons
    const totalPriceElement = document.getElementById('total-price');
    const orderForm = document.getElementById('order-form');
    const selectedLotsInput = document.getElementById('selected-lots');
    const orderBtn = orderForm.querySelector('button');

    let selectedLots = [];
    let totalPrice = 0.00;

    // Afficher les lots quand on clique sur le bouton
    if (showLotsBtn) {
        showLotsBtn.addEventListener('click', function() {
            forfaitsContainer.style.display = 'none';
            lotsContainer.style.display = 'flex';
        });
    }
    retour.addEventListener('click', function() {
        forfaitsContainer.style.display = 'flex';
        lotsContainer.style.display = 'none';
    });

    // Sélection des lots via les boutons seulement
    lotButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation(); // Empêche la propagation à la div parente
            const lotCard = this.closest('.lot'); // Remonte à la div lot parente
            const lotId = lotCard.getAttribute('data-lot-id');
            const lotPrice = parseInt(lotCard.querySelector('.lot-price').textContent);

            if (lotCard.classList.contains('selected')) {
                // Désélection
                lotCard.classList.remove('selected');
                this.textContent = 'SELECTIONNER'; // Change le texte du bouton
                selectedLots = selectedLots.filter(id => id !== lotId);
                totalPrice -= lotPrice;
            } else {
                // Sélection
                lotCard.classList.add('selected');
                this.textContent = 'DESELECTIONNER'; // Change le texte du bouton
                selectedLots.push(lotId);
                totalPrice += lotPrice;
            }

            // Mise à jour de l'UI
            updateSelectionUI();
        });
    });

    function updateSelectionUI() {
        totalPriceElement.textContent = totalPrice.toFixed(2);
        selectedLotsInput.value = JSON.stringify(selectedLots);
        orderBtn.disabled = selectedLots.length === 0;
    }
});