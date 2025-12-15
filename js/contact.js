// ============================================
// CONTACT PAGE LOGIC
// ============================================

document.addEventListener('DOMContentLoaded', function () {
    initContactPage();
});

// Initialisation de la page contact
function initContactPage() {
    const contactForm = document.getElementById('contact-form');
    // Si la form n'existe pas, on arrête (pas sur la bonne page)
    if (!contactForm) return;

    const formMessage = document.getElementById('form-message');

    contactForm.addEventListener('submit', function (e) {
        e.preventDefault();

        if (validateForm()) {
            // Simulation d'envoi du formulaire
            formMessage.innerHTML = '<div class="alert alert-success">Votre message a été envoyé avec succès !</div>';
            contactForm.reset();

            // Effacer le message après 5 secondes
            setTimeout(() => {
                formMessage.innerHTML = '';
            }, 5000);
        }
    });


}

// Validation du formulaire de contact
function validateForm() {
    const name = document.getElementById('name');
    const email = document.getElementById('email');
    const message = document.getElementById('message');

    let isValid = true;

    // Réinitialiser les états de validation
    name.classList.remove('is-invalid');
    email.classList.remove('is-invalid');
    message.classList.remove('is-invalid');

    // Validation du nom
    if (!name.value.trim()) {
        name.classList.add('is-invalid');
        isValid = false;
    }

    // Validation de l'email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.value.trim() || !emailRegex.test(email.value)) {
        email.classList.add('is-invalid');
        isValid = false;
    }

    // Validation du message
    if (!message.value.trim()) {
        message.classList.add('is-invalid');
        isValid = false;
    }

    return isValid;
}


