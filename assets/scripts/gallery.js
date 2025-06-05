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
    let addImgInMission = "/user/add-in-my-mission";
    let rmImgInMission = "/user/remove-in-my-mission";
    let isChecked = document.getElementById("mission-" + idMission + "-" + idPhoto).checked;

    let url = isChecked
        ? addImgInMission
        : rmImgInMission;

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
            if (!response.ok)
                throw new Error("Erreur HTTP " + response.status);
            else
            {
                console.log("idmission "+ idMission);
                // cache la photo si on est sur le détail d'une mission et qu'on la retire de celle ci
                if (currentMissionId != null && currentMissionId === idMission)
                    document.querySelector(`.gallery-card[data-id="${idPhoto}"]`).remove();
            }
            return response.json();
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

async function confirmDelete() {
    if (deletePostId) {
        try {
            const deleteUrl = deleteUrlTemplate.replace('__ID__', deletePostId);
            const response = await fetch(deleteUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                try
                {
                    document.getElementById(`photo-${deletePostId}`).remove();
                }
                catch (e) {
                    document.getElementById(`mission-${deletePostId}`).remove();
                }
                closeDeleteModal();
            } else {
                const data = await response.json();
                alert("Erreur : " + (data.error || "Impossible de supprimer la photo."));
            }
        } catch (error) {
            console.error("Erreur réseau :", error);
            alert("Erreur réseau. Réessayez plus tard.");
        }
    }
}

let popupMapInstance = null;
let mapInitialized = false;

function openMapPopup(lat, lng, especeName = "") {
    const overlay = document.getElementById("map-popup-overlay");
    const mapContainer = document.getElementById("fullscreen-map");

    // Affiche le popup et bloque le scroll
    overlay.style.display = "flex";
    document.body.style.overflow = 'hidden';

    // Si la map n'est pas encore créée
    if (!mapInitialized) {
        // Petit délai pour s'assurer que le conteneur est visible
        setTimeout(() => {
            popupMapInstance = L.map(mapContainer, {
                center: [lat, lng],
                zoom: 8,
                minZoom: 3,
                maxBounds: [[-89.9, -180], [89.9, 180]],
                maxBoundsViscosity: 1.0,
                worldCopyJump: false,
                zoomSnap: 0.25 // optionnel : rend le zoom plus fluide
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                noWrap: true
            }).addTo(popupMapInstance);

            // Ajoute le marqueur initial
            L.marker([lat, lng])
                .addTo(popupMapInstance)
                .bindPopup(`<b>${especeName}</b><br>Lat: ${lat}, Lng: ${lng}`)
                .openPopup();

            // Marque comme initialisée
            mapInitialized = true;

            // Corrige l'affichage
            setTimeout(() => popupMapInstance.invalidateSize(), 100);
        }, 50);
    } else {
        // Si la carte existe déjà, réutilise-la
        popupMapInstance.setView([lat, lng], 5);

        // Supprime les anciens marqueurs
        popupMapInstance.eachLayer(layer => {
            if (layer instanceof L.Marker) popupMapInstance.removeLayer(layer);
        });

        // Ajoute le nouveau marqueur
        L.marker([lat, lng])
            .addTo(popupMapInstance)
            .bindPopup(`<b>${especeName}</b><br>Lat: ${lat}, Lng: ${lng}`)
            .openPopup();

        // Corrige potentiels problèmes d’affichage
        setTimeout(() => popupMapInstance.invalidateSize(), 100);
    }
}

function closeMapPopup() {
    document.getElementById("map-popup-overlay").style.display = "none";
    document.body.style.overflow = 'auto';
    // Ne pas supprimer la carte, pour la réutiliser
}

function setCurrentMissionId(id) {
    currentMissionId = id;
}