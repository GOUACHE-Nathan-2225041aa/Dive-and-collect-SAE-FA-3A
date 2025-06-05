if (!localStorage.getItem("pageReloaded")) {
    localStorage.setItem("pageReloaded", "true");
    location.reload();
} else {
    localStorage.removeItem("pageReloaded");
}

document.addEventListener('page:loaded', () => {
    const map = L.map('map', {
        center: [46.603354, 1.888334],
        zoom: 5,
        minZoom: 2, // ou même 0 pour un zoom arrière maximal
        maxZoom: 10,
        maxBounds: [[-89.9, -180], [89.9, 180]],
        maxBoundsViscosity: 1.0,
        worldCopyJump: false,
        zoomSnap: 0.25 // optionnel : rend le zoom plus fluide
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        noWrap: true,
        bounds: [[-85, -180], [85, 180]]
    }).addTo(map);


    const points = window.poissonPoints || [];

    const markersByEspece = {};

    points.forEach(p => {
        const marker = L.marker([p.lat, p.lng]).bindPopup(`<b>${p.espece}</b><br>Lat: ${p.lat}, Lng: ${p.lng}`);

        if (!markersByEspece[p.espece]) {
            markersByEspece[p.espece] = [];
        }
        markersByEspece[p.espece].push(marker);
        marker.addTo(map);
    });

    document.querySelectorAll('.espece-checkbox').forEach(cb => {
        cb.addEventListener('change', updateMarkers);
    });

    updateMarkers();

    // Gère la visibilité selon l’état des checkboxs
    function updateMarkers() {
        const checkboxes = document.querySelectorAll('.espece-checkbox');
        checkboxes.forEach(cb => {
            const espece = cb.value;
            const markers = markersByEspece[espece] || [];
            markers.forEach(marker => {
                if (cb.checked) {
                    if (!map.hasLayer(marker)) {
                        marker.addTo(map);
                    }
                } else {
                    if (map.hasLayer(marker)) {
                        map.removeLayer(marker);
                    }
                }
            });
        });
    }

// Attache les listeners
    document.querySelectorAll('.espece-checkbox').forEach(cb => {
        cb.addEventListener('change', updateMarkers);
    });

// Bouton pour tout cocher / décocher
    const toggleAllCheckbox = document.getElementById('toggle-all');
    toggleAllCheckbox.addEventListener('click', () => {
        const allCheckboxes = document.querySelectorAll('.espece-checkbox');
        const allChecked = [...allCheckboxes].every(cb => cb.checked);

        allCheckboxes.forEach(cb => cb.checked = !allChecked);

        updateMarkers();
    });

// Initialisation
    if (typeof espece !== "undefined" && espece) {
        toggleAllCheckbox.click();
        document.getElementById(espece).checked = true;
    }
    updateMarkers();
});