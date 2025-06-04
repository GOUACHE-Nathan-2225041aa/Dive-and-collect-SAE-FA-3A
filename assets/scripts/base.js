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

let currentImageIndex = 0;
let lightboxImages = [];

function updateLightboxImages(customImages = null) {
    lightboxImages = customImages ? Array.from(customImages) : Array.from(document.querySelectorAll('.gallery-image, .mission-details .carousel img'));
}

function openLightbox(img, customImages = null) {
    updateLightboxImages(customImages);
    currentImageIndex = lightboxImages.findIndex(img => img === img);

    const overlay = document.getElementById('lightboxOverlay');
    const lightboxImg = document.getElementById('lightboxImage');

    lightboxImg.src = img.src;
    overlay.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function navigateLightbox(direction, event) {
    event.stopPropagation();
    currentImageIndex = (currentImageIndex + direction + lightboxImages.length) % lightboxImages.length;
    document.getElementById('lightboxImage').src = lightboxImages[currentImageIndex].src;
}

function closeLightbox() {
    document.getElementById('lightboxOverlay').style.display = 'none';
    document.body.style.overflow = '';
}

// Navigation clavier
document.addEventListener('keydown', (e) => {
    const overlay = document.getElementById('lightboxOverlay');
    if (overlay.style.display !== 'flex') return;

    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') navigateLightbox(-1, {stopPropagation:()=>{}});
    if (e.key === 'ArrowRight') navigateLightbox(1, {stopPropagation:()=>{}});
});

// Initialisation
document.addEventListener('page:loaded', () => {
    updateLightboxImages();
    lightboxImages.forEach(img => {
        img.style.cursor = 'zoom-in';
        img.addEventListener('click', () => openLightbox(img));
    });
});