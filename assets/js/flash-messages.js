document.addEventListener('DOMContentLoaded', function() {

  let flashMessages = document.querySelectorAll('.flash-message');


  flashMessages.forEach(function(message) {
    // Get timer duration from data attribute
    let timer = parseInt(message.dataset.timer);

    // Hide flash message after specified time
    setTimeout(function() {
      message.classList.add('hidden');
    }, timer);
  });
});
