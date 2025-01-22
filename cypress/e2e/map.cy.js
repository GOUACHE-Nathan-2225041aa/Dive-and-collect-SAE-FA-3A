describe('map', () => {
it('map load', () => {
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
  cy.get('#link-spot').click()
  cy.get('.leaflet-marker-icon');
});

});
