document.addEventListener('DOMContentLoaded', function() {
  const thumbnails = document.querySelectorAll('.image-thumbnail');
  const modal = document.getElementById('photoModal');
  const modalImage = document.getElementById('modalPhoto');

  if (thumbnails.length > 0 && modal && modalImage) {
    thumbnails.forEach(function(thumbnail) {
      thumbnail.addEventListener('click', function() {
        modalImage.src = thumbnail.src;
        modal.classList.remove('hidden');
      });
    });

    modal.addEventListener('click', function(event) {
      if (event.target === modal) {
        modal.classList.add('hidden');
      }
    });
  }
});
