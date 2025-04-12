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

//likes system
document.getElementById('like-btn')?.addEventListener('click', async function () {
    const articleId = this.dataset.id;
    const icon = document.getElementById('like-icon');
    const count = document.getElementById('like-count');

    // Désactiver le bouton pendant la requête AJAX
    this.disabled = true;

    const response = await fetch(`/article/${articleId}/like`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });

    const data = await response.json();

    // Réactiver le bouton après la requête AJAX
    this.disabled = false;

    if (data.liked !== undefined) {
        // Mettre à jour le compteur de likes
        count.textContent = data.count;

        // Feedback visuel : changement d'icône et couleur
        if (data.liked) {
            this.classList.remove('text-gray-700', 'bg-gray-200');
            this.classList.add('text-white', 'bg-blue-500');
            icon.classList.remove('fa-thumbs-up');
            icon.classList.add('fa-thumbs-down');
        } else {
            this.classList.remove('text-white', 'bg-blue-500');
            this.classList.add('text-gray-700', 'bg-gray-200');
            icon.classList.remove('fa-thumbs-down');
            icon.classList.add('fa-thumbs-up');
        }
    }
});

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');