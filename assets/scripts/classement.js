document.addEventListener('page:loaded', function() {
    const selectRole = document.querySelector('.select-role');
    let currentRole = selectRole.dataset.defaultRole; // Valeur par défaut

    // Initialisation - masque tout sauf les ONGs
    document.querySelectorAll('.ligne').forEach(ligne => {
        console.log(ligne.dataset.role);

        ligne.style.display = ligne.dataset.role === currentRole ? 'flex' : 'none';
        ligne.style.opacity = '1';
    });

    // Gestion des clics
    selectRole.querySelectorAll('button').forEach(button => {
        button.addEventListener('click', function() {
            // Met à jour les classes actives
            selectRole.querySelectorAll('button').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');

            // Change le rôle courant
            currentRole = this.dataset.role;

            // Affiche/masque les éléments
            document.querySelectorAll('.ligne').forEach(ligne => {
                ligne.style.display = ligne.dataset.role === currentRole ? 'flex' : 'none';
            });
        });
    });
});
