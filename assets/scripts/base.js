
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

document.addEventListener('turbo:before-render', removePageSpecificCSS);

document.addEventListener('turbo:load', function() {
    setTimeout(function(){
        document.dispatchEvent(new CustomEvent('page:loaded'));
    }, 20);
});

function openLightbox(src) {
    const overlay = document.getElementById('lightboxOverlay');
    const lightboxImg = document.getElementById('lightboxImage');
    lightboxImg.src = src;
    overlay.style.display = 'flex';
    document.body.style.overflow = 'hidden'; // ➜ désactive le scroll
}

function closeLightbox() {
    const overlay = document.getElementById('lightboxOverlay');
    overlay.style.display = 'none';
    document.body.style.overflow = ''; // ➜ réactive le scroll
}

document.querySelectorAll('.gallery-image').forEach(img => {
    img.style.cursor = 'zoom-in';
    img.addEventListener('click', () => openLightbox(img.src));
});