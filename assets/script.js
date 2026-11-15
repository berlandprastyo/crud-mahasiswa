
const hamburger = document.getElementById("hamburger");
const navbarMenu = document.getElementById("navbar-menu");
const button = document.getElementById("toggle");

if (hamburger && navbarMenu) {
    
    hamburger.addEventListener("click", function () {
        navbarMenu.classList.toggle("active");
        
        const isOpen = navbarMenu.classList.contains("active");
        
        hamburger.setAttribute("aria-expanded", isOpen);
        hamburger.textContent = isOpen ? "✕" : "☰";
    });
}
if (button) {
    const icon = button.querySelector("i");

    button.addEventListener("click", function () {
        const password = document.getElementById("password");

        const isPassword = password.type === "password";

        password.type = isPassword ? "text" : "password";

        icon.classList.toggle("fa-eye", isPassword);
        icon.classList.toggle("fa-eye-slash", !isPassword);
    });
}