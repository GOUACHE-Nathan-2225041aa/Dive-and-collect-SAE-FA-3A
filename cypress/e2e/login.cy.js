describe('login', () => {
  it('login success', () => {
    // Visiter la page d'accueil de l'application
    cy.visit('https://localhost/');

    // Vérifier que le texte "FAITE DE LA" est présent sur la page,
    // ce qui signifie que la page s'est bien chargée et que le contenu attendu est visible.
    cy.contains('FAITE DE LA');

    // Cliquer sur l'icône du burger menu pour ouvrir le menu de navigation sur mobile
    cy.get('#nav-burger').click();

    // Cliquer sur le lien ou le bouton de connexion dans le menu mobile
    cy.get('#connexion-mobile').click();

    // Vérifier que la page de connexion est bien affichée en cherchant le texte "Connexion"
    cy.contains('Connexion');

    // Remplir le champ de l'email avec l'adresse 'toto@gmail.com'
    cy.get('#inputEmail').type('toto@gmail.com');

    // Remplir le champ du mot de passe avec 'Totototo1234'
    cy.get('#inputPassword').type('Totototo1234');

    // Cliquer sur le bouton de soumission du formulaire de connexion
    cy.get('#login-submit').click();

    // Vérifier que l'utilisateur est redirigé vers une page contenant le texte "Paramètres",
    // ce qui indique une connexion réussie
    cy.contains('Paramètres');
  });
  it('faild login', () => {
    cy.visit('https://localhost/');

    // Vérifier que le texte "Welcome to Symfony" est présent
    cy.contains('FAITE DE LA');
    cy.get('#nav-burger').click()
    cy.get('#connexion-mobile').click()
    cy.contains('Connexion');
    cy.get('#inputEmail').type('toto@gmail.com')
    cy.get('#inputPassword').type('Totototo1')
    cy.get('#login-submit').click()
    cy.contains('Erreur')
  });
  it('Deconnexion', () => {
    // Visiter la page d'accueil
    cy.visit('https://localhost/');

    // Vérifier que le texte "Welcome to Symfony" est présent
    cy.contains('FAITE DE LA');
    cy.get('#nav-burger').click()
    cy.get('#connexion-mobile').click()
    cy.contains('Connexion');
    cy.get('#inputEmail').type('toto@gmail.com')
    cy.get('#inputPassword').type('Totototo1234')
    cy.get('#login-submit').click()
    cy.contains('Paramètres');
    cy.get('#nav-burger').click()
    cy.get('#déconnexion-mobile').click()
    cy.contains('FAITE DE LA');
  });
});
