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

function OnchangeCheckBoxMission(idMission, idPhoto) {
    console.log("ajoute photo d'id " + idPhoto + " dans la mission d'id " + idMission);
    let isChecked = document.getElementById("mission-" + idMission + "-" + idPhoto).checked;

    let url = isChecked
        ? addImgInMission
        : rmImgInMission;

    console.log("url : " + url);

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            idMission: idMission,
            idPhoto: idPhoto
        })
    })
        .then(response => {
            if (!response.ok) throw new Error("Erreur HTTP " + response.status);
            return response.json();
        })
        .then(data => {
            console.log("Réponse : ", data);
        })
        .catch(error => {
            console.error('Erreur lors de la requête AJAX :', error);
        });
}



function likePhoto(element) {
    const postId = element.getAttribute('data-post-id');
    const likeUrl = likeUrlTemplate.replace('__ID__', postId);
    const icon = element.querySelector('.like-icon');
    const countLabel = element.querySelector('.like-count');

    console.log("url : "+likeUrl);

    fetch(likeUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
        .then(response => response.json())
        .then(data => {
            // Update icon
            icon.src = data.liked
                ? like_on      // Like ajouté
                : like_off ;       // Like retiré (icone par défaut)
            // Update count
            countLabel.textContent = data.count;
        })
        .catch(error => {
            console.error('Error liking the post:', error);
        });
}

let deletePostId = null;

function openDeleteModal(postId) {
    deletePostId = postId;
    document.getElementById('deleteModal').style.display = 'block';
}

function closeDeleteModal() {
    deletePostId = null;
    document.getElementById('deleteModal').style.display = 'none';
}

function confirmDelete() {
    if (deletePostId) {
        window.location.href = '/photo/delete/' + deletePostId;
    }
}
