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

    // Initialisation de la carte Google Maps
    // Vérifier si la fonction Google Maps est chargée, sinon attendre ou laisser wrapper faire
    if (typeof window.initMap === 'function') {
        window.initMap();
    }
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

// Initialisation de la carte Google Maps
// Note: Cette fonction doit être globale pour être appelée par le callback de l'API Google Maps
window.initMap = function () {
    const mapElement = document.getElementById('map');
    if (!mapElement) return;

    // Coordonnées du café (exemple: Paris)
    const cafeLocation = { lat: 35.82628511092442, lng: 10.589582474757044 };

    // Créer la carte
    const map = new google.maps.Map(mapElement, {
        zoom: 15,
        center: cafeLocation,
        styles: [
            {
                "featureType": "all",
                "elementType": "geometry.fill",
                "stylers": [{ "weight": "2.00" }]
            },
            {
                "featureType": "all",
                "elementType": "geometry.stroke",
                "stylers": [{ "color": "#9c9c9c" }]
            },
            {
                "featureType": "all",
                "elementType": "labels.text",
                "stylers": [{ "visibility": "on" }]
            },
            {
                "featureType": "landscape",
                "elementType": "all",
                "stylers": [{ "color": "#f2f2f2" }]
            },
            {
                "featureType": "landscape",
                "elementType": "geometry.fill",
                "stylers": [{ "color": "#ffffff" }]
            },
            {
                "featureType": "landscape.man_made",
                "elementType": "geometry.fill",
                "stylers": [{ "color": "#ffffff" }]
            },
            {
                "featureType": "poi",
                "elementType": "all",
                "stylers": [{ "visibility": "off" }]
            },
            {
                "featureType": "road",
                "elementType": "all",
                "stylers": [{ "saturation": -100 }, { "lightness": 45 }]
            },
            {
                "featureType": "road",
                "elementType": "geometry.fill",
                "stylers": [{ "color": "#eeeeee" }]
            },
            {
                "featureType": "road",
                "elementType": "labels.text.fill",
                "stylers": [{ "color": "#7b7b7b" }]
            },
            {
                "featureType": "road",
                "elementType": "labels.text.stroke",
                "stylers": [{ "color": "#ffffff" }]
            },
            {
                "featureType": "road.highway",
                "elementType": "all",
                "stylers": [{ "visibility": "simplified" }]
            },
            {
                "featureType": "road.arterial",
                "elementType": "labels.icon",
                "stylers": [{ "visibility": "off" }]
            },
            {
                "featureType": "transit",
                "elementType": "all",
                "stylers": [{ "visibility": "off" }]
            },
            {
                "featureType": "water",
                "elementType": "all",
                "stylers": [{ "color": "#46bcec" }, { "visibility": "on" }]
            },
            {
                "featureType": "water",
                "elementType": "geometry.fill",
                "stylers": [{ "color": "#c8d7d4" }]
            },
            {
                "featureType": "water",
                "elementType": "labels.text.fill",
                "stylers": [{ "color": "#070707" }]
            },
            {
                "featureType": "water",
                "elementType": "labels.text.stroke",
                "stylers": [{ "color": "#ffffff" }]
            }
        ]
    });

    // Ajouter un marqueur
    new google.maps.Marker({
        position: cafeLocation,
        map: map,
        title: 'Le Café Local'
    });
};
