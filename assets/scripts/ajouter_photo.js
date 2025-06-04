document.addEventListener("DOMContentLoaded", function () {
    const map = L.map('map').setView([46.603354, 1.888334], 5); // France par défaut

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker;

    map.on('click', function (e) {
        const { lat, lng } = e.latlng;

        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }

        const latInput = document.getElementById('photo_type_form_coordonnees_latitude');
        const lngInput = document.getElementById('photo_type_form_coordonnees_longitude');
        if (latInput && lngInput) {
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
        }
    });
});
