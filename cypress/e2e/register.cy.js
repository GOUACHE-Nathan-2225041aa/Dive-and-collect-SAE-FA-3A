describe('Register', () => {
  it('creat profile', () => {
    // Visiter la page d'accueil
    cy.visit('https://localhost/');

    // Vérifier que le texte "Welcome to Symfony" est présent
    cy.contains('FAITE DE LA');
    cy.get('#nav-burger').click()
    cy.get('#connexion-mobile').click()
    cy.contains('Connexion');
    cy.get('#Inscrivez-vous').click()
    cy.contains('Je crée mon compte');
    cy.get('#registration_form_firstname').type('toto2')
    cy.get('#registration_form_lastname').type('toto2')
    cy.get('#registration_form_email').type('toto2@gmail.com')
    cy.get('#registration_form_password_first').type('Totototo1234')
    cy.get('#registration_form_password_second').type('Totototo1234')
    cy.get('#registration_form_submit').click({ force: true });
    cy.contains('Paramètres');
  });
  it('faild creat profile', () => {
    // Visiter la page d'accueil
    cy.visit('https://localhost/');

    // Vérifier que le texte "Welcome to Symfony" est présent
    cy.contains('FAITE DE LA');
    cy.get('#nav-burger').click()
    cy.get('#connexion-mobile').click()
    cy.contains('Connexion');
    cy.get('#Inscrivez-vous').click()
    cy.contains('Je crée mon compte');
    cy.get('#registration_form_firstname').type('toto2')
    cy.get('#registration_form_lastname').type('toto2')
    cy.get('#registration_form_email').type('toto2@gmail.com')
    cy.get('#registration_form_password_first').type('Totototo1234')
    cy.get('#registration_form_password_second').type('Totototo14')
    cy.get('#registration_form_submit').click({ force: true });
    cy.get('.flash-message');
  });
});
