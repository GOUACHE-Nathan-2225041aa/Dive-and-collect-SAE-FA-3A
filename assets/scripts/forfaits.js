document.addEventListener('page:loaded', function() {
    const showLotsBtn = document.querySelector('.show-lots');
    const retour = document.querySelector('.retour');
    const forfaitsContainer = document.getElementById('forfaits-container');
    const lotsContainer = document.getElementById('lots-container');
    const lotButtons = document.querySelectorAll('.lot .button'); // Sélectionne uniquement les boutons
    const orderLotsForm = document.getElementById('order-lots-form');
    const selectedLotsInput = document.getElementById('selected-lots');
    const orderLotsBtn = orderLotsForm?.querySelector('button');
    let selectedLots = [];
    let lotsTotalPrice = 0.00;

    const forfaitButtons = document.querySelectorAll('.forfait .button:not(.show-lots)'); // Sélectionne uniquement les boutons des forfaits
    const selectedForfaitInput = document.getElementById('selected-forfait');
    const orderForfaitForm = document.getElementById('order-forfait-form');
    const orderForfaitBtn = orderForfaitForm?.querySelector('button');

    // Afficher les lots quand on clique sur le bouton
    showLotsBtn?.addEventListener('click', function() {
        forfaitsContainer.style.display = 'none';
        lotsContainer.style.display = 'flex';
    });
    retour?.addEventListener('click', function() {
        forfaitsContainer.style.display = 'flex';
        lotsContainer.style.display = 'none';
        uniformHeightTop();
    });

    // Sélection des lots via les boutons seulement
    lotButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation(); // Empêche la propagation à la div parente
            const lotCard = this.closest('.lot'); // Remonte à la div lot parente
            const lotId = lotCard.getAttribute('data-lot-id');
            const lotPrice = parseFloat(lotCard.querySelector('.price').textContent);

            if (lotCard.classList.contains('selected')) {
                // Désélection
                lotCard.classList.remove('selected');
                this.textContent = 'SELECTIONNER'; // Change le texte du bouton
                selectedLots = selectedLots.filter(id => id !== lotId);
                lotsTotalPrice -= lotPrice;
                if (lotsTotalPrice < 0) {
                    lotsTotalPrice = 0; // Assure que le total ne devient pas négatif parce que js
                }
            } else {
                // Sélection
                lotCard.classList.add('selected');
                this.textContent = 'DESELECTIONNER'; // Change le texte du bouton
                selectedLots.push(lotId);
                lotsTotalPrice += lotPrice;
            }

            // Mise à jour de l'UI
            updateSelectionUI();
        });
    });

    function updateSelectionUI() {
        animateCounter(lotsTotalPrice);
        selectedLotsInput.value = JSON.stringify(selectedLots);
        orderLotsBtn.disabled = selectedLots.length === 0;
    }

    // Sélection des forfaits via les boutons
    forfaitButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation(); // Empêche la propagation à la div parente
            const forfaitCard = this.closest('.forfait'); // Remonte à la div forfait parente
            const forfaitId = forfaitCard.getAttribute('data-forfait-id');
            const forfaitNom = forfaitCard.querySelector('.nom').textContent;

            if (forfaitCard.classList.contains('selected')) {
                // Désélection
                forfaitCard.classList.remove('selected');
                this.textContent = 'SELECTIONNER'; // Change le texte du bouton
                selectedForfaitInput.value = ''; // Réinitialise l'input caché
                orderForfaitBtn.textContent = 'COMMANDER'; // Réinitialise le texte du bouton de commande
                orderForfaitBtn.disabled = true; // Désactive le bouton de commande
            } else {
                // Sélection

                // Vérifie si un autre forfait est déjà sélectionné
                const previouslySelected = document.querySelector('.forfait.selected');
                if (previouslySelected && previouslySelected !== forfaitCard) {
                    previouslySelected.classList.remove('selected');
                    previouslySelected.querySelector('.button').textContent = 'SELECTIONNER'; // Réinitialise le texte du bouton
                }

                forfaitCard.classList.add('selected');
                this.textContent = 'DESELECTIONNER'; // Change le texte du bouton
                selectedForfaitInput.value = forfaitId; // Met à jour l'input caché
                orderForfaitBtn.textContent = `COMMANDER\n${forfaitNom}`; // Met à jour le texte du bouton de commande
                orderForfaitBtn.disabled = false; // Active le bouton de commande
            }
        });
    });

    document.querySelectorAll('.forfait ul li').forEach(item => {
        const header = item.querySelector('.header'); // Adaptez à votre sélecteur
        const description = item.querySelector('.description');
        const arrow = item.querySelector('.arrow');

        header.addEventListener('click', () => {
            if (item.classList.contains('active')) {
                arrow.style.transform = 'rotate(0deg)';
                // Fermeture - utilise la hauteur réelle avant de réduire
                description.style.maxHeight = `${description.scrollHeight}px`;
                // Force le recalcul pour démarrer l'animation
                void description.offsetHeight;
                description.style.maxHeight = '0';
            } else {
                arrow.style.transform = 'rotate(90deg)';
                // Ouverture - animation vers la hauteur réelle
                description.style.maxHeight = '0';
                void description.offsetHeight;
                description.style.maxHeight = `${description.scrollHeight}px`;
            }

            item.classList.toggle('active');

            // Réinitialisation après animation
            setTimeout(() => {
                if (item.classList.contains('active')) {
                    description.style.maxHeight = 'none';
                }
            }, 500);
        });
    });
});

function uniformHeightTop() {
    const tops = document.querySelectorAll('.forfait:not([data-role="FORFAIT_ONG_PERSO"]) .top'); // ou un sélecteur plus spécifique

    // Trouver la hauteur maximale
    let heightMax = 0;
    tops.forEach(top => {
        top.style.height = 'auto';
        const height = top.offsetHeight;
        if (height > heightMax) {
            heightMax = height; // Mettre à jour la height maximale
        }
    });

    // Appliquer la height maximale à tous
    tops.forEach(top => {
        top.style.height = `${heightMax}px`;
    });
}

// Appeler la fonction au chargement et lors des redimensionnements
window.addEventListener('resize', uniformHeightTop);
document.addEventListener('page:loaded', uniformHeightTop);
document.addEventListener('turbo:load', uniformHeightTop);

function animateCounter(targetValue, duration = 500) {
    const element = document.getElementById('total-price');
    const startValue = parseFloat(element.textContent);
    const startTime = performance.now();

    function updateValue(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // Animation ease-out
        const easedProgress = 1 - Math.pow(1 - progress, 3);
        const currentValue = startValue + (targetValue - startValue) * easedProgress;

        element.textContent = currentValue.toFixed(2);

        if (progress < 1) {
            requestAnimationFrame(updateValue);
        }
    }

    requestAnimationFrame(updateValue);
}