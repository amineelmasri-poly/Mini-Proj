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
            // Collect form data
            const formData = new FormData();
            formData.append('name', document.getElementById('name').value);
            formData.append('email', document.getElementById('email').value);
            formData.append('message', document.getElementById('message').value);

            // Show loading state
            formMessage.innerHTML = '<div class="alert alert-info"><i class="fas fa-spinner fa-spin me-2"></i>Envoi en cours...</div>';

            // Send to PHP backend
            fetch('php/contact_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    formMessage.innerHTML = '<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>' + data.message + '</div>';
                    contactForm.reset();
                } else {
                    formMessage.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>' + data.message + '</div>';
                }

                // Effacer le message après 5 secondes
                setTimeout(() => {
                    formMessage.innerHTML = '';
                }, 5000);
            })
            .catch(error => {
                formMessage.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>Erreur lors de l\'envoi. Veuillez réessayer.</div>';
            });
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


