document.addEventListener('DOMContentLoaded', function() {
  const burgerButton = document.getElementById('burger-button');
  const closeButton = document.getElementById('close-button');
  const burgerMenu = document.getElementById('burger-menu');

  if (burgerButton && closeButton && burgerMenu) {
    burgerButton.addEventListener('click', function() {
      burgerMenu.classList.remove('-translate-x-full');
      burgerMenu.classList.add('translate-x-0');
    });

    closeButton.addEventListener('click', function() {
      burgerMenu.classList.remove('translate-x-0');
      burgerMenu.classList.add('-translate-x-full');
    });
  }
});
