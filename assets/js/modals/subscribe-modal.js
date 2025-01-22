document.addEventListener('DOMContentLoaded', function () {
  const subscriptionButtons = document.querySelectorAll('.btn-subscription');
  const closeBtns = document.querySelectorAll('.close-btn');

  function toggleModal(modalId, action) {
    const modal = document.getElementById(modalId);
    if (modal) {
      if (action === 'show') {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      } else if (action === 'hide') {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }
  }

  // Show the modal associated with a subscribe button
  subscriptionButtons.forEach(button => {
    button.addEventListener('click', function () {
      const subscriptionId = button.getAttribute('data-subscription-id');
      const existingSubscriptionActive = button.getAttribute('data-existing-subscription') === 'true';
      const modalId = existingSubscriptionActive
        ? `subscriptionModal${subscriptionId}`
        : `noSubscriptionModal${subscriptionId}`;

      toggleModal(modalId, 'show');
    });
  });


  closeBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      const modalId = btn.closest('.fixed').id;
      toggleModal(modalId, 'hide');
    });
  });
});
