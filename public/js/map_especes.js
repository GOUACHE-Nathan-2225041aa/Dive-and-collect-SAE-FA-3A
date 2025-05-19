document.addEventListener('DOMContentLoaded', () => {
    const map = L.map('map').setView([0, 0], 2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
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

    function updateMarkers() {
        const checkboxes = document.querySelectorAll('.espece-checkbox');
        checkboxes.forEach(cb => {
            const espece = cb.value;
            const visible = cb.checked;
            if (markersByEspece[espece]) {
                markersByEspece[espece].forEach(marker => {
                    if (visible) {
                        marker.addTo(map);
                    } else {
                        map.removeLayer(marker);
                    }
                });
            }
        });
    }

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
    updateMarkers();

});


