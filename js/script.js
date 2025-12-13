// Données des produits pour la page menu
const products = [
    {
        id: 1,
        name: "Espresso",
        category: "boissons-chaudes",
        price: 2.50,
        image: "assets/images/espresso.png",
        description: "Un espresso intense et aromatique",
        fullDescription: "Notre espresso est préparé avec des grains de café Arabica soigneusement torréfiés pour un goût riche et équilibré.",
        popularity: 5
    },
    {
        id: 2,
        name: "Cappuccino",
        category: "boissons-chaudes",
        price: 3.50,
        image: "assets/images/cappuccino.png",
        description: "Cappuccino crémeux avec de la mousse de lait",
        fullDescription: "Un mélange parfait d'espresso, de lait chaud et de mousse de lait onctueuse, saupoudré de cacao.",
        popularity: 4
    },
    {
        id: 3,
        name: "Croissant",
        category: "patisseries",
        price: 2.00,
        image: "assets/images/croissant.png",
        description: "Croissant beurré et feuilleté",
        fullDescription: "Nos croissants sont préparés quotidiennement avec du beurre AOP pour une texture légère et feuilletée.",
        popularity: 5
    },
    {
        id: 4,
        name: "Sandwich Jambon-Fromage",
        category: "sandwiches",
        price: 5.50,
        image: "assets/images/sandwich.png",
        description: "Sandwich au jambon et fromage sur pain artisanal",
        fullDescription: "Un sandwich gourmet préparé avec du jambon de qualité supérieure, du fromage emmental et notre pain artisanal.",
        popularity: 4
    },
    {
        id: 5,
        name: "Thé Vert",
        category: "boissons-chaudes",
        price: 2.80,
        image: "assets/images/the-vert.png",
        description: "Thé vert rafraîchissant et détoxifiant",
        fullDescription: "Notre thé vert est sélectionné pour ses propriétés antioxydantes et son goût délicat.",
        popularity: 3
    },
];

// Initialisation du site
document.addEventListener('DOMContentLoaded', function () {
    // Navigation fluide
    initSmoothScroll();

    // Effet de scroll sur la navbar
    initNavbarScroll();

    // Initialisation de la page menu si on est sur cette page
    if (document.getElementById('products-container')) {
        initMenuPage();
    }

    // Initialisation de la page contact si on est sur cette page
    if (document.getElementById('contact-form')) {
        initContactPage();
    }

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

// Initialisation de la page menu
function initMenuPage() {
    // Afficher tous les produits au chargement
    displayProducts(products);

    // Gestion des filtres
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Retirer la classe active de tous les boutons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Ajouter la classe active au bouton cliqué
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');
            filterProducts(filter);
        });
    });

    // Gestion du tri
    const sortOptions = document.querySelectorAll('.sort-option');
    sortOptions.forEach(option => {
        option.addEventListener('click', function (e) {
            e.preventDefault();
            const sortType = this.getAttribute('data-sort');
            sortProducts(sortType);
        });
    });
}

// Affichage des produits
function displayProducts(productsToDisplay) {
    const container = document.getElementById('products-container');
    container.innerHTML = '';

    if (productsToDisplay.length === 0) {
        container.innerHTML = '<div class="col-12 text-center"><p>Aucun produit trouvé.</p></div>';
        return;
    }

    productsToDisplay.forEach(product => {
        const productCard = createProductCard(product);
        container.appendChild(productCard);
    });
}

// Création d'une carte produit
function createProductCard(product) {
    const col = document.createElement('div');
    col.className = 'col-md-6 col-lg-4';

    col.innerHTML = `
        <div class="card product-card h-100" data-category="${product.category}">
            <img src="${product.image}" class="card-img-top product-image" alt="${product.name}">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">${product.name}</h5>
                <p class="card-text">${product.description}</p>
                <div class="mt-auto">
                    <p class="product-price">${product.price.toFixed(2)} €</p>
                    <button class="btn btn-outline-primary btn-sm view-details" data-id="${product.id}">Voir détails</button>
                </div>
            </div>
        </div>
    `;

    // Ajouter l'événement pour afficher les détails
    const detailsButton = col.querySelector('.view-details');
    detailsButton.addEventListener('click', function () {
        showProductDetails(product);
    });

    return col;
}

// Filtrage des produits
function filterProducts(category) {
    let filteredProducts;

    if (category === 'all') {
        filteredProducts = products;
    } else {
        filteredProducts = products.filter(product => product.category === category);
    }

    displayProducts(filteredProducts);
}

// Tri des produits
function sortProducts(sortType) {
    let sortedProducts = [...products];

    switch (sortType) {
        case 'price-asc':
            sortedProducts.sort((a, b) => a.price - b.price);
            break;
        case 'price-desc':
            sortedProducts.sort((a, b) => b.price - a.price);
            break;
        case 'popularity':
            sortedProducts.sort((a, b) => b.popularity - a.popularity);
            break;
        default:
            break;
    }

    displayProducts(sortedProducts);
}

// Affichage des détails d'un produit
function showProductDetails(product) {
    // Créer un modal Bootstrap pour afficher les détails
    const modalHtml = `
        <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel">${product.name}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="${product.image}" class="img-fluid rounded" alt="${product.name}">
                            </div>
                            <div class="col-md-6">
                                <p class="lead">${product.fullDescription}</p>
                                <p class="h4 text-primary">${product.price.toFixed(2)} €</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Ajouter le modal au body s'il n'existe pas déjà
    if (!document.getElementById('productModal')) {
        document.body.insertAdjacentHTML('beforeend', modalHtml);
    } else {
        // Mettre à jour le modal existant
        document.getElementById('productModalLabel').textContent = product.name;
        document.querySelector('#productModal .modal-body').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <img src="${product.image}" class="img-fluid rounded" alt="${product.name}">
                </div>
                <div class="col-md-6">
                    <p class="lead">${product.fullDescription}</p>
                    <p class="h4 text-primary">${product.price.toFixed(2)} €</p>
                </div>
            </div>
        `;
    }

    // Afficher le modal
    const productModal = new bootstrap.Modal(document.getElementById('productModal'));
    productModal.show();
}

// Initialisation de la page contact
function initContactPage() {
    const contactForm = document.getElementById('contact-form');
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
    initMap();
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
function initMap() {
    // Coordonnées du café (exemple: Paris)
    const cafeLocation = { lat: 35.82628511092442, lng: 10.589582474757044 };

    // Créer la carte
    const map = new google.maps.Map(document.getElementById('map'), {
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

// ============================================
// LOGIQUE DE RÉSERVATION DE GROUPE
// ============================================

// État des précommandes
const preorders = {}; // { productId: quantity }

// Initialisation de la section de réservation
function initReservationSection() {
    const reservationSection = document.getElementById('reservation');
    if (!reservationSection) return;

    // Remplir la liste de précommande
    renderPreorderList();

    // Gestionnaire pour le bouton "Générer Reçu"
    const generateBtn = document.getElementById('btn-generate-receipt');
    if (generateBtn) {
        generateBtn.addEventListener('click', generateReceipt);
    }
}

// Appeler l'initialisation si on est sur la page menu
if (document.getElementById('reservation')) {
    // On ajoute un écouteur au DOMContentLoaded existant ou on l'exécute directement
    // Comme le script est chargé à la fin du body, on peut l'appeler direct si le DOM est prêt,
    // mais par sécurité on l'ajoute à la fonction d'init globale modifyant initMenuPage
}

// Rendu de la liste de précommande
function renderPreorderList() {
    const container = document.getElementById('preorder-list');
    if (!container) return;

    container.innerHTML = '';

    // Grouper par catégorie pour une meilleure lisibilité
    const categories = {
        'boissons-chaudes': 'Boissons Chaudes',
        'patisseries': 'Pâtisseries',
        'sandwiches': 'Sandwiches'
    };

    for (const [catKey, catName] of Object.entries(categories)) {
        const catProducts = products.filter(p => p.category === catKey);

        if (catProducts.length > 0) {
            const catHeader = document.createElement('h6');
            catHeader.className = 'text-primary mt-3 mb-2 px-2';
            catHeader.textContent = catName;
            container.appendChild(catHeader);

            catProducts.forEach(product => {
                const item = document.createElement('div');
                item.className = 'preorder-item';
                item.innerHTML = `
                    <div class="preorder-details">
                        <p class="preorder-name">${product.name}</p>
                        <p class="preorder-price">${product.price.toFixed(2)} €</p>
                    </div>
                    <div class="preorder-controls">
                        <button type="button" class="btn btn-outline-secondary btn-quantity" onclick="updatePreorder(${product.id}, -1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="quantity-display" id="qty-${product.id}">0</span>
                        <button type="button" class="btn btn-outline-primary btn-quantity" onclick="updatePreorder(${product.id}, 1)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                `;
                container.appendChild(item);
            });
        }
    }
}

// Mise à jour d'une précommande
function updatePreorder(productId, change) {
    if (!preorders[productId]) preorders[productId] = 0;

    preorders[productId] += change;

    // Empêcher les quantités négatives
    if (preorders[productId] < 0) preorders[productId] = 0;

    // Mettre à jour l'affichage de la quantité
    const qtyDisplay = document.getElementById(`qty-${productId}`);
    if (qtyDisplay) {
        qtyDisplay.textContent = preorders[productId];
        // Style visuel si quantité > 0
        if (preorders[productId] > 0) {
            qtyDisplay.classList.add('text-primary');
        } else {
            qtyDisplay.classList.remove('text-primary');
        }
    }

    // Mettre à jour le total
    updatePreorderTotal();
}

// Calcul et mise à jour du total
function updatePreorderTotal() {
    let total = 0;

    for (const [productId, qty] of Object.entries(preorders)) {
        if (qty > 0) {
            const product = products.find(p => p.id == productId);
            if (product) {
                total += product.price * qty;
            }
        }
    }

    const totalDisplay = document.getElementById('preorder-total');
    if (totalDisplay) {
        totalDisplay.textContent = total.toFixed(2) + ' €';
    }

    return total;
}

// Génération du reçu et validation
function generateReceipt() {
    // 1. Validation du formulaire
    const form = document.getElementById('reservation-form');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const guests = document.getElementById('res-guests').value;
    if (guests < 7) {
        alert("La réservation de groupe nécessite un minimum de 7 personnes.");
        return;
    }

    // 2. Récupération des données
    const name = document.getElementById('res-name').value;
    const phone = document.getElementById('res-phone').value;
    const email = document.getElementById('res-email').value;
    const date = document.getElementById('res-date').value;
    const time = document.getElementById('res-time').value;

    // 3. Construction du reçu HTML
    const totalPreorder = updatePreorderTotal();
    const receiptContent = document.getElementById('receipt-content');

    let preorderHtml = '';
    let hasPreorder = false;

    // Construire la liste des articles
    for (const [productId, qty] of Object.entries(preorders)) {
        if (qty > 0) {
            hasPreorder = true;
            const product = products.find(p => p.id == productId);
            const lineTotal = product.price * qty;
            preorderHtml += `
                <div class="receipt-row">
                    <span>${qty}x ${product.name}</span>
                    <span>${lineTotal.toFixed(2)} €</span>
                </div>
            `;
        }
    }

    if (!hasPreorder) {
        preorderHtml = '<div class="receipt-row text-muted text-center">Aucune précommande</div>';
    }

    receiptContent.innerHTML = `
        <div class="receipt">
            <div class="receipt-header">
                <img src="assets/images/logo.png" alt="Logo" class="receipt-logo">
                <div class="receipt-title">Le Café Local</div>
                <div class="receipt-info">CONFIRMATION DE RÉSERVATION</div>
                <div class="receipt-info small">Date d'émission: ${new Date().toLocaleDateString()}</div>
            </div>

            <div class="receipt-section">
                <span class="receipt-label">CLIENT</span>
                <div class="receipt-row"><strong>Nom:</strong> ${name}</div>
                <div class="receipt-row"><strong>Tél:</strong> ${phone}</div>
                <div class="receipt-row"><strong>Email:</strong> ${email}</div>
            </div>

            <div class="receipt-section">
                <span class="receipt-label">RÉSERVATION</span>
                <div class="receipt-row"><strong>Date:</strong> ${date}</div>
                <div class="receipt-row"><strong>Heure:</strong> ${time}</div>
                <div class="receipt-row"><strong>Invités:</strong> ${guests} personnes</div>
            </div>

            <div class="receipt-section">
                <span class="receipt-label">PRÉCOMMANDE</span>
                ${preorderHtml}
                
                <div class="receipt-total">
                    <div class="receipt-row">
                        <span>TOTAL ESTIMÉ</span>
                        <span>${totalPreorder.toFixed(2)} €</span>
                    </div>
                </div>
            </div>

            <div class="receipt-footer">
                <p>Merci pour votre réservation !</p>
                <p>Veuillez présenter ce reçu à votre arrivée.</p>
                <p>Pour toute modification, contactez-nous au +216 55 555 555</p>
            </div>
        </div>
    `;

    // 4. Afficher le modal
    const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
    receiptModal.show();
}

// Hook pour initialiser la section réservation depuis initMenuPage
const originalInitMenuPage = initMenuPage;
initMenuPage = function () {
    originalInitMenuPage();
    initReservationSection();
};