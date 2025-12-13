// ============================================
// MAIN SCRIPTS - SHARED LOGIC
// ============================================

document.addEventListener('DOMContentLoaded', function () {
    // Navigation fluide
    initSmoothScroll();

    // Effet de scroll sur la navbar
    initNavbarScroll();

    // Initialisation des effets de survol
    initHoverEffects();
});

// Effet de scroll sur la navbar
function initNavbarScroll() {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;

    let lastScroll = 0;

    window.addEventListener('scroll', function () {
        const currentScroll = window.pageYOffset;

        if (currentScroll > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }

        lastScroll = currentScroll;
    });
}

// Navigation fluide
function initSmoothScroll() {
    const navLinks = document.querySelectorAll('a.nav-link');

    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            // Si le lien pointe vers une ancre sur la même page
            if (this.getAttribute('href').startsWith('#')) {
                e.preventDefault();

                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
}

// Initialisation des effets de survol
function initHoverEffects() {
    // Effet de survol sur les cartes de produit
    document.addEventListener('mouseover', function (e) {
        if (e.target.closest('.product-card')) {
            const card = e.target.closest('.product-card');
            card.style.transform = 'translateY(-5px)';
            card.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.1)';
        }
    });

    document.addEventListener('mouseout', function (e) {
        if (e.target.closest('.product-card')) {
            const card = e.target.closest('.product-card');
            card.style.transform = 'translateY(0)';
            card.style.boxShadow = '';
        }
    });

    // Effet de survol sur le bouton CTA
    const ctaButton = document.querySelector('.cta-button');
    if (ctaButton) {
        ctaButton.addEventListener('mouseenter', function () {
            this.style.transform = 'scale(1.05)';
        });

        ctaButton.addEventListener('mouseleave', function () {
            this.style.transform = 'scale(1)';
        });
    }
}
