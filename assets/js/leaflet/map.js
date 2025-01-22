document.addEventListener('DOMContentLoaded', function () {
  // Vérification des éléments de données
  const spotsElement = document.getElementById('spots-data');
  const divesElement = document.getElementById('dives-data');
  const newDiveButton = document.getElementById('newDiveButton');

  if (!spotsElement || !divesElement) {
    console.error('Required data elements not found');
    return;
  }

  const spots = JSON.parse(spotsElement.getAttribute('data-spots'));
  const dives = JSON.parse(divesElement.getAttribute('data-dives'));

  // Configuration des icônes
  const premiumIcon = L.icon({
    iconUrl: '/leaflet_img/marker-icon-2x-premium.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34]
  });

  const defaultIcon = L.icon({
    iconUrl: '/leaflet_img/marker-icon-2x.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34]
  });

  // Initialisation de la carte
  const map = L.map('map').setView([43.293234807686524, 5.389847718361297], 7);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map);

  let markers = [];

  // Fonction pour afficher les plongées
  function showDives(filteredDives) {
    const divesListContainer = document.querySelector('#divesList');
    if (!divesListContainer) return;

    divesListContainer.innerHTML = filteredDives.map(dive => `
      <a href="/dive/${dive.id}" class="w-full flex my-2">
        <div class="flex flex-col justify-center items-center">
          <div class="flex">
            <div class="p-2">
              <img class="w-14 h-14 rounded-full"
                   src="${dive.avatar ? 'uploaded_avatar/' + dive.avatar : '/image/avatard.png'}"
                   alt="avatar"/>
            </div>
            <div class="justify-center flex flex-col text-left">
              <p class="text-xl truncate max-w-48">${dive.title}</p>
              <p class="text-xs">${dive.date}</p>
              <p class="text-xs">${dive.userLast} ${dive.userFirst}</p>
              <p class="text-xs text-gray-500">${dive.spotName || 'Spot non spécifié'}</p>
            </div>
          </div>
        </div>
      </a>
    `).join('');
  }

  // Fonction pour afficher toutes les interventions
  function showAllDives() {
    showDives(dives);
    updateModalTitle('Toutes les interventions');
    document.querySelector('#modalIntervention')?.classList.remove('hidden');
    document.querySelector('#interventionBtn')?.classList.add('hidden');
    newDiveButton.classList.add('hidden');
  }

  // Fonction pour fermer le modal
  function closeModal() {
    document.querySelector('#modalIntervention')?.classList.add('hidden');
    document.querySelector('#interventionBtn')?.classList.remove('hidden');
    newDiveButton.classList.add('hidden');
  }

  // Gestionnaire d'événement pour le bouton de fermeture
  document.querySelector('#fermerintervention')?.addEventListener('click', closeModal);

  // Gestionnaire d'événement pour le bouton d'intervention
  document.querySelector('#interventionBtn')?.addEventListener('click', showAllDives);

  // Fonction pour mettre à jour le titre du modal
  function updateModalTitle(title) {
    const modalTitleElement = document.querySelector('#modalTitle');
    if (modalTitleElement) {
      modalTitleElement.textContent = title;
    }
  }

  // Fonction pour afficher les plongées d'un spot
  function showDivesForSpot(spotId, spotName) {
    const filteredDives = dives.filter(dive => dive.spotId === spotId);
    showDives(filteredDives);
    updateModalTitle(spotName);
    document.querySelector('#modalIntervention')?.classList.remove('hidden');
    newDiveButton.classList.remove('hidden');

    const newDiveLink = newDiveButton.querySelector('a');
    newDiveLink.setAttribute('data-spot-id', spotId);

    const baseUrl = newDiveLink.getAttribute('href').split('?')[0];
    newDiveLink.href = `${baseUrl}?spotId=${spotId}`;
  }

  // Création des marqueurs
  function createMarkers(spotsToShow = spots) {
    // Nettoyer les marqueurs existants
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];

    spotsToShow.forEach(spot => {
      const popupContent = `
        <div class="flex flex-col justify-center items-center min-h-[30%]">
          <h3 class="font-bold">${spot.name}</h3>
          ${spot.image ? `<img src="${spot.image}" class="rounded my-2" alt="${spot.name}" style="min-width: 15rem; height: auto;">` : ''}
          ${spot.premium ? '<span class="mb-4 mt-1 text-outremer">Spot Premium</span>' : ''}
          <button id="showDives${spot.id}" class="show-dives-btn rounded-full bg-fushia hover:bg-fushia_hover px-4 py-2 text-white font-bold">Voir les plongées</button>
        </div>
      `;

      const marker = L.marker([spot.latitude, spot.longitude], {
        icon: spot.premium ? premiumIcon : defaultIcon
      })
        .addTo(map)
        .bindPopup(popupContent)
        .on('popupopen', () => {
          document.querySelector(`#showDives${spot.id}`)?.addEventListener('click', () => {
            showDivesForSpot(spot.id, spot.name);
          });
        });

      markers.push(marker);
    });
  }

  // Initialisation des marqueurs
  createMarkers();

  // Gestion des filtres
  const searchInput = document.querySelector('#filter_spot_search');
  const checkboxes = document.querySelectorAll('#filter_spot_checkboxes input[type="checkbox"]');

  function filterSpots() {
    if (!searchInput) return;

    const searchText = searchInput.value.toLowerCase();
    const selectedFilters = Array.from(checkboxes)
      .filter(checkbox => checkbox.checked)
      .map(checkbox => checkbox.value.toLowerCase());

    const filteredSpots = spots.filter(spot => {
      const matchesSearch = spot.name.toLowerCase().includes(searchText);
      let matchesFilter = selectedFilters.length === 0 ||
        (selectedFilters.includes('marin') && selectedFilters.includes('eau douce')) ||
        (selectedFilters.includes('marin') && spot.marin) ||
        (selectedFilters.includes('eau douce') && !spot.marin);

      return matchesSearch && matchesFilter;
    });

    createMarkers(filteredSpots);
  }

  // Événements des filtres
  searchInput?.addEventListener('input', filterSpots);
  checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', filterSpots);
  });

  createMarkers();
  showAllDives();
});
