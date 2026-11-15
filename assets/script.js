
const hamburger = document.getElementById("hamburger");
const navbarMenu = document.getElementById("navbar-menu");

hamburger.addEventListener("click", function () {
    navbarMenu.classList.toggle("active");

    const isOpen = navbarMenu.classList.contains("active");

    hamburger.setAttribute("aria-expanded", isOpen);
    hamburger.textContent = isOpen ? "✕" : "☰";
});
