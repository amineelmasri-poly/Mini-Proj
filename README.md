# Le Café Local - Project Website

A modern, responsive static website for "Le Café Local", featuring a dynamic menu, group reservation system, and contact form.

## 🌟 Key Features

### 1. Home Page (`index.html`)
- **Hero Carousel**: Engaging image slider with animated text.
- **About Section**: Introduction to the café's history.
- **Values**: key highlight cards (Local Products, Friendly Service, Warm Atmosphere).
- **Responsive Navigation**: Bootstrap navbar that adapts to mobile screens.

### 2. Interactive Menu (`menu.html`)
- **Product Display**: Dynamic rendering of products from JavaScript data.
- **Categorization**: Filter products by "Hot Drinks", "Pastries", or "Sandwiches".
- **Sorting**: Sort items by Price (Low/High) or Popularity.
- **Product Details**: Modal popup with full description and pricing when clicking "Voir détails".

### 3. Group Reservation & Pre-order System (`menu.html`)
*Designed for groups of 7 or more people.*
- **Reservation Form**: Collects contact info, date, time, and guest count (validation min. 7).
- **Pre-order Interface**: Allows guests to pre-select items (Calculates total cost in real-time).
- **Instant Receipt**: Generates a professional, printable receipt summary in a modal popup.
- **Print-Friendly**: Special CSS ensures only the receipt is printed when using the print function.

### 4. Contact Page (`contact.html`)
- **Contact Form**: Validates inputs (Name, Email, Message) and mimics a submission success.
- **Location**: Integration placeholder for Google Maps.
- **Info Card**: Displays address, phone, and opening hours.

## 📂 Project Structure

```
Mini-Proj/
│
├── css/
│   └── style.css       # Custom styles & Bootstrap overrides
│
├── js/
│   └── script.js       # Core logic (Menu data, Filtering, Reservation, Maps)
│
├── assets/
│   └── images/         # Project images (logos, products, slides)
│
├── index.html          # Landing page
├── menu.html           # Menu & Reservation page
├── contact.html        # Contact page
└── README.md           # Project documentation
```

## 🚀 How to Run

Since this is a static website, no backend server is required.

1.  **Clone or Download** the project folder.
2.  **Open** the project folder.
3.  **Double-click** on `index.html` to open it in your default web browser.

## 🛠️ Technologies Used

- **HTML5**: Semantic structure.
- **CSS3**: Custom properties (variables), Flexbox, Grid, Media Queries for print & mobile.
- **JavaScript (ES6+)**: DOM manipulation, Array methods (filter/sort), dynamic HTML generation.
- **Bootstrap 5.3**: Responsive grid system, Modals, Navbar, Carousel.
- **Font Awesome 6.4**: Icons for UI elements.
- **Google Fonts**: Roboto (Headings) & Open Sans (Body).

## 📝 Notes

- **Data Persistence**: As a static site, there is no database. Reservations/Orders are not permanently saved; the receipt is generated for the client to save/print locally.
- **Google Maps**: The map in `contact.html` requires a valid API Key to function fully.
