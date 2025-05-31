
const hamburgerMenu = document.querySelector(".hamburger");
const body = document.body;


function toggleMenu(x) {
    x.classList.toggle("change");

    const dropdown = document.getElementById("dropdownMenu");
    if (dropdown.style.width === "100%") {
        dropdown.style.width = "0";
    } else {
        dropdown.style.width = "100%";
    }

    if (x.classList.contains("active")) {
        document.body.classList.add("no-scroll");
    } else {
        document.body.classList.remove("no-scroll");
    }
};

// Supprime les styles css ne correspondant pas à la page actuelle
function removePageSpecificCSS() {
    document.querySelectorAll('link[data-page-specific]').forEach(link => {
        if (link.dataset.pageSpecific !== location.pathname) {
            link.remove();
        }
    });
}

// Écoute tous les événements pertinents pour supprimer les styles CSS spécifiques à la page
['turbo:load', 'popstate'].forEach(event => {
    document.addEventListener(event, removePageSpecificCSS, { passive: true });
document.addEventListener('turbo:before-render', removePageSpecificCSS);
});
document.addEventListener('click', (e) => {
    if (e.target.closest('a[href]')) {
        removePageSpecificCSS();
    }
}, { passive: true });