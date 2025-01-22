document.addEventListener('DOMContentLoaded', function () {
  const container = document.querySelector('.img-container');
  const items = document.querySelectorAll('.dive-img');
  const nextButton = document.querySelector('#right-chevron');
  const prevButton = document.querySelector('#left-chevron');

  if (container && items.length > 0 && nextButton && prevButton) {
    const itemWidth = items[0].getBoundingClientRect().width + 20; // Element width + spacing
    let index = 0;

    nextButton.addEventListener('click', () => {
      if (index < items.length - 3) {
        index = Math.min(index + 1, items.length - 3);
        updateCarousel();
      }
    });

    prevButton.addEventListener('click', () => {
      index = Math.max(index - 1, 0);
      updateCarousel();
    });

    function updateCarousel() {
      const offset = -index * itemWidth;
      container.style.transform = `translateX(${offset}px)`;
    }
  }
});
