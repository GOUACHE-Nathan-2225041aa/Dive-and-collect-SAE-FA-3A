
const hamburgerMenu = document.querySelector(".hamburger");
const body = document.body;


function toggleMenu(x) {
    x.classList.toggle("change");

    const dropdown = document.getElementById("dropdownMenu");
    if (dropdown.style.width === "100%") {
        dropdown.style.width = "0";
    } else {
        dropdown.style.width = "100%";
    }

    if (x.classList.contains("active")) {
        document.body.classList.add("no-scroll");
    } else {
        document.body.classList.remove("no-scroll");
    }
};