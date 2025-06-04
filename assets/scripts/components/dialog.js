function showDialog(dialogId = 'genericDialog') {
    const dialog = document.getElementById(dialogId);
    dialog.style.display = 'flex';
    setTimeout(() => {
        dialog.style.opacity = '1';
        dialog.querySelector('.dialog-container').style.transform = 'translateY(0)';
    }, 10);
}

function closeDialog(dialogId = 'genericDialog') {
    const dialog = document.getElementById(dialogId);
    dialog.style.opacity = '0';
    dialog.querySelector('.dialog-container').style.transform = 'translateY(-20px)';
    setTimeout(() => {
        dialog.style.display = 'none';
    }, 300);
}

// Fermeture en cliquant à l'extérieur
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.dialog-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDialog(this.id);
            }
        });
    });
});

async function loadContent(url) {
    // Afficher un loader
    document.querySelector('.dialog-content').innerHTML = '<div class="loader" style="height: 50rem;">Chargement...</div>';
    showDialog();

    try {
        const response = await fetch(url);
        // Extrait uniquement le contenu de la page
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = await response.text();
        const blocContent = tempDiv.querySelector('.bloc_content');
        blocContent.classList.remove('bloc_content');

        const dialogContent = document.querySelector('.dialog-content');
        dialogContent.innerHTML = blocContent.innerHTML;

        document.dispatchEvent(new Event('page:loaded')); // Déclenche l'événement pour les scripts de la page chargée
    } catch (error) {
        document.querySelector('.dialog-content').innerHTML = `
            <div class="error-message">
                Erreur lors du chargement
                <button onclick="loadContent(url)">Réessayer</button>
            </div>
        `;
    }
}