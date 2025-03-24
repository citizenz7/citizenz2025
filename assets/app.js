// import './bootstrap.js';

import '@fortawesome/fontawesome-free/css/all.css';
import './styles/app.css';

document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menu-toggle");
    const menuClose = document.getElementById("menu-close");
    const mobileMenu = document.getElementById("mobile-menu");

    menuToggle.addEventListener("click", function () {
        mobileMenu.classList.remove("-translate-x-full");
    });

    menuClose.addEventListener("click", function () {
        mobileMenu.classList.add("-translate-x-full");
    });

    // Fermer le menu si on clique en dehors
    mobileMenu.addEventListener("click", function (event) {
        if (event.target === mobileMenu) {
            mobileMenu.classList.add("-translate-x-full");
        }
    });
});

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');