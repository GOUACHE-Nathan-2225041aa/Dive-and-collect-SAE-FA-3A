function scrollCarousel(carousel, direction) {
    const cardWidth = carousel.querySelector('.card-carousel').offsetWidth + 20; // 20 = gap
    carousel.scrollBy({
        left: direction * cardWidth,
        behavior: 'smooth'
    });
}