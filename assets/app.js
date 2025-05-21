import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

function toggleMenu(x) {
    x.classList.toggle("change");

    var dropdown = document.getElementById("dropdownMenu");
    if (dropdown.style.width === "100%") {
        dropdown.style.width = "0";
    } else {
        dropdown.style.width = "100%";
    }
}

const hamburgerMenu = document.querySelector(".hamburger");
const body = document.body;


hamburgerMenu.addEventListener("click", function() {
    console.log("blablabla");
    this.classList.toggle("active");

    const dropdown = document.getElementById("dropdownMenu");
    if (dropdown.style.width === "100%") {
        dropdown.style.width = "0";
    } else {
        dropdown.style.width = "100%";
    }

    if (this.classList.contains("active")) {
        document.body.classList.add("no-scroll");
    } else {
        document.body.classList.remove("no-scroll");
    }
});


document.addEventListener("DOMContentLoaded", function () {
    const dropdowns = document.querySelectorAll(".custom-dropdown");

    dropdowns.forEach(function (dropdown) {
        const dropdownContent = dropdown.querySelector(".custom-dropdown-content");
        const dropdownButton = dropdown.querySelector(".headerButton");

        dropdownButton.addEventListener("click", function (event) {
            event.stopPropagation(); // Empêche la propagation du clic vers d'autres éléments

            // Ferme tous les menus avant d'ouvrir le menu sélectionné
            dropdowns.forEach(function (otherDropdown) {
                const otherContent = otherDropdown.querySelector(".custom-dropdown-content");
                if (otherContent !== dropdownContent) {
                    otherContent.classList.remove("show");
                }
            });

            // Bascule l'affichage du menu cliqué
            dropdownContent.classList.toggle("show");
        });
    });

    // Cacher tous les menus si l'on clique à l'extérieur
    document.addEventListener("click", function (event) {
        dropdowns.forEach(function (dropdown) {
            const dropdownContent = dropdown.querySelector(".custom-dropdown-content");
            dropdownContent.classList.remove("show");
        });
    });
});