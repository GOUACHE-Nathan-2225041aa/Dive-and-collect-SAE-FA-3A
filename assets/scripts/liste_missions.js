document.addEventListener('page:loaded', function() {
    document.querySelectorAll('.image-carousel').forEach(carousel => {
        const slides = carousel.querySelectorAll('.slide');
        const prevBtn = carousel.querySelector('.prev-arrow');
        const nextBtn = carousel.querySelector('.next-arrow');
        const imageCounter = carousel.querySelector('.mission-image-counter');
        let currentIndex = 0;

        function loadImage(slide, loadNext = true) {
            const lazyImg = slide.querySelector('img.lazy');
            if (lazyImg && lazyImg.dataset.src) {
                lazyImg.src = lazyImg.dataset.src;
                lazyImg.classList.remove('lazy');
            }
            if (loadNext) {
                if (currentIndex < slides.length - 1) loadImage(slides[currentIndex + 1], false);
            }
        }

        function updateButtons() {
            prevBtn.disabled = currentIndex === 0;
            nextBtn.disabled = currentIndex === slides.length - 1;
        }

        function goToSlide(index) {
            currentIndex = index;
            loadImage(slides[index]);
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
                slide.classList.toggle('prev', i === index - 1);
            });
            updateButtons();
        }

        // click on image counter to load all images
        imageCounter.addEventListener('click', () => {
            slides.forEach((slide, index) => {
                loadImage(slide, false);
            });
        });

        // Navigation
        nextBtn.addEventListener('click', () => {
            if (currentIndex < slides.length - 1) {
                goToSlide(currentIndex + 1);
            }
        });

        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                goToSlide(currentIndex - 1);
            }
        });

        // Initialisation
        updateButtons();
    });
});

function normalizeString(str) {
    return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
}

function filterMissions() {
    const input = normalizeString(document.getElementById('searchInput').value);
    const cards = document.querySelectorAll('.mission-card');

    function checkSpecies(card) {
        return Array.from(card.querySelectorAll('.species-name'))
            .some(el => normalizeString(el.textContent).includes(input));
    }

    function checkLocations(card) {
        return Array.from(card.querySelectorAll('.lieu'))
            .some(el => normalizeString(el.textContent).includes(input));
    }

    function checkProfile(card) {
        return normalizeString(card.querySelector('.author-name').textContent).includes(input);
    }

    function checkTitle(card) {
        return normalizeString(card.querySelector('.mission-title').textContent).includes(input);
    }

    function checkDateAdded(card) {
        return normalizeString(card.querySelector('.date-added').textContent).includes(input);
    }

    function checkDateStart(card) {
        return normalizeString(card.querySelector('.date-start').textContent).includes(input);
    }

    function checkDateEnd(card) {
        return normalizeString(card.querySelector('.date-end').textContent).includes(input);
    }

    cards.forEach(card => {
        const shouldShow = checkSpecies(card) || checkLocations(card) || checkProfile(card) || checkTitle(card) || checkDateAdded(card) || checkDateStart(card) || checkDateEnd(card);
        card.style.display = shouldShow ? 'flex' : 'none';
    });
}

function parseDMY(dateStr) {
    const [d, m, y] = dateStr.split('/');
    return new Date(y, m - 1, d, 12, 0, 0); // Midday pour éviter les problèmes de fuseau
}

function sortMissions() {
    const sortValue = document.getElementById('sortSelect').value;
    const container = document.querySelector('.missions');
    const cards = Array.from(container.querySelectorAll('.mission-card'));

    if (!sortValue) return; // rien à trier

    cards.sort((a, b) => {
        let aVal, bVal;

        if (sortValue === "date-added") {
            // récupère la date au format lisible et convertit en timestamp
            aVal = parseDMY(a.querySelector('.date-added').textContent).getTime();
            bVal = parseDMY(b.querySelector('.date-added').textContent).getTime();
            // trie par date descendante (plus récent d'abord)
            return bVal - aVal;
        } else if (sortValue === 'date-start') {
            aVal = parseDMY(a.querySelector('.date-start').textContent).getTime();
            bVal = parseDMY(b.querySelector('.date-start').textContent).getTime();
            return bVal - aVal;
        } else if (sortValue === 'date-end') {
            aVal = parseDMY(a.querySelector('.date-end').textContent).getTime();
            bVal = parseDMY(b.querySelector('.date-end').textContent).getTime();
            return bVal - aVal;
        } else if (sortValue === 'profile') {
            aVal = a.querySelector('.author-name').textContent.toLowerCase();
            bVal = b.querySelector('.author-name').textContent.toLowerCase();
            return aVal.localeCompare(bVal);
        } else if (sortValue === 'title') {
            aVal = a.querySelector('.mission-title').textContent.toLowerCase();
            bVal = b.querySelector('.mission-title').textContent.toLowerCase();
            return aVal.localeCompare(bVal);
        }
    });

    // ré-insérer dans l'ordre trié
    cards.forEach(card => container.appendChild(card));
}