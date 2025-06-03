function normalizeString(str) {
    return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
}

function filterGallery() {
    const input = normalizeString(document.getElementById('searchInput').value);
    const cards = document.querySelectorAll('.gallery-card');

    cards.forEach(card => {
        const species = normalizeString(card.querySelector('.species-name').textContent);
        const location = normalizeString(card.querySelector('.photo-meta').textContent);
        const profile = normalizeString(card.querySelector('.author-name').textContent);

        if (species.includes(input) || location.includes(input) || profile.includes(input)) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}
function toggleMissions(button) {
    const missionList = button.nextElementSibling;
    missionList.classList.toggle('hidden');
    button.textContent = missionList.classList.contains('hidden') ? '+' : '−';
}

function sortGallery() {
    const sortValue = document.getElementById('sortSelect').value;
    const container = document.querySelector('.gallery-wrapper');
    const cards = Array.from(container.querySelectorAll('.gallery-card'));

    if (!sortValue) return; // rien à trier

    cards.sort((a, b) => {
        let aVal, bVal;

        if (sortValue === 'date') {
            // récupère la date au format lisible et convertit en timestamp
            aVal = new Date(a.querySelector('.photo-meta').textContent.match(/Date added:\s*(.*)/)[1]).getTime();
            bVal = new Date(b.querySelector('.photo-meta').textContent.match(/Date added:\s*(.*)/)[1]).getTime();
            // trie par date descendante (plus récent d'abord)
            return bVal - aVal;
        } else if (sortValue === 'profile') {
            aVal = a.querySelector('.author-name').textContent.toLowerCase();
            bVal = b.querySelector('.author-name').textContent.toLowerCase();
            return aVal.localeCompare(bVal);
        }
    });

    // ré-insérer dans l'ordre trié
    cards.forEach(card => container.appendChild(card));
}
