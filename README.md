# Le Café Local - Project Website

A modern, responsive website for "Le Café Local" with **full PHP backend**, featuring dynamic menu management, reservation system with email confirmations, contact form, and complete admin dashboard.

## 🌟 Key Features

### 1. Home Page (`index.html`)
- **Hero Carousel**: Engaging image slider with animated text
- **About Section**: Introduction to the café's history
- **Values**: Key highlight cards (Local Products, Friendly Service, Warm Atmosphere)
- **Responsive Navigation**: Bootstrap navbar that adapts to mobile screens

### 2. Interactive Menu (`menu.html`)
- **Dynamic Product Display**: Products loaded from MySQL database
- **Categorization**: Filter products by "Hot Drinks", "Pastries", or "Sandwiches"
- **Sorting**: Sort items by Price (Low/High) or Popularity
- **Product Details**: Modal popup with full description and pricing
- **Real-time Availability**: Admin can mark items as available/unavailable

### 3. Group Reservation & Pre-order System (`menu.html`) ✨ NEW
*Designed for groups of 7 or more people.*
- **Reservation Form**: Collects contact info, date, time, and guest count (validation min. 7)
- **Pre-order Interface**: Allows guests to pre-select items with real-time cost calculation
- **Database Storage**: All reservations saved to MySQL
- **Email Confirmation**: Automatic professional email receipt sent to customers
- **Admin Notification**: Email alert sent to admin for new reservations
- **Print-Friendly Receipt**: Professional receipt generation

### 4. Contact Page (`contact.html`) ✨ NEW
- **Contact Form with Backend**: Form submissions saved to database
- **Dual Email System**: 
  - Admin receives inquiry notification
  - Customer receives auto-reply confirmation
- **Admin Management**: View, mark as read/replied, or delete messages
- **Location Map**: Embedded Google Maps integration
- **Info Card**: Displays address, phone, and opening hours

### 5. Admin Dashboard 🔐 NEW
**Access:** `/php/admin/login.php`  
**Default Credentials:** admin / admin123

#### Dashboard Features:
- **Statistics Overview**:
  - Reservations today
  - Pending reservations
  - New messages
  - Today's revenue
- **Quick Actions**: View recent reservations and messages
- **Responsive Design**: Works on all devices

#### Menu Management:
- ✅ Add/Edit/Delete products
- ✅ Upload product images
- ✅ Update prices and descriptions
- ✅ Toggle availability (available/sold out)
- ✅ Set popularity ratings (1-5 stars)
- ✅ Category management

#### Reservations Management:
- ✅ View all reservations with filters
- ✅ Confirm/Cancel reservations
- ✅ View preorder details
- ✅ Filter by status (pending/confirmed/cancelled)
- ✅ Delete reservations
- ✅ Full customer contact information

#### Messages Management:
- ✅ View all contact form submissions
- ✅ Mark as read/replied
- ✅ Quick reply via email link
- ✅ Delete messages
- ✅ Filter by status (new/read/replied)

## 📂 Project Structure

```
Mini-Proj/
│
├── php/                        # 🆕 PHP Backend
│   ├── config.php             # Database configuration
│   ├── contact_handler.php    # Contact form processor
│   ├── reservation_handler.php # Reservation processor
│   ├── api/
│   │   └── get_products.php   # Products API endpoint
│   └── admin/
│       ├── login.php          # Admin login page
│       ├── dashboard.php      # Admin dashboard
│       ├── menu_management.php # Menu CRUD operations
│       ├── reservations.php   # Reservations management
│       ├── contacts.php       # Messages management
│       ├── auth_check.php     # Authentication middleware
│       ├── logout.php         # Logout handler
│       ├── sidebar.php        # Admin sidebar component
│       ├── navbar.php         # Admin navbar component
│       └── admin-style.css    # Admin dashboard styles
│
├── sql/                        # 🆕 Database
│   └── database.sql           # Complete database schema
│
├── css/
│   ├── main.css               # Global styles & Bootstrap overrides
│   ├── home.css               # Home page specific styles
│   ├── menu.css               # Menu page styles
│   └── contact.css            # Contact page styles
│
├── js/
│   ├── main.js                # Core logic (Navigation, animations)
│   ├── menu.js                # Menu logic (Products API, Reservation handler)
│   └── contact.js             # Contact form AJAX submission
│
├── assets/
│   └── images/                # Project images (logos, products, slides)
│
├── index.html                 # Landing page
├── menu.html                  # Menu & Reservation page
├── contact.html               # Contact page
├── README.md                  # Project documentation
├── INSTALLATION.md            # 🆕 Setup instructions
└── LICENSE                    # MIT License
```

## 🚀 How to Run

### Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx) or PHP built-in server

### Quick Start

1. **Import Database:**
```bash
mysql -u root -p < sql/database.sql
```

2. **Configure Database:**
Edit `php/config.php` with your database credentials.

3. **Start Server:**
```bash
cd "d:\PERSONEL\CYCLE ING\3INF\WEB1\Mini-Proj"
php -S localhost:8000
```

4. **Access Application:**
- **Website:** http://localhost:8000
- **Admin Dashboard:** http://localhost:8000/php/admin/login.php

### Default Admin Login:
- Username: `admin`
- Password: `admin123`

**⚠️ Change the password after first login!**

📖 **For detailed installation instructions, see [INSTALLATION.md](INSTALLATION.md)**

## 🛠️ Technologies Used

### Frontend:
- **HTML5**: Semantic structure
- **CSS3**: Custom properties (variables), Flexbox, Grid, Media Queries
- **JavaScript (ES6+)**: 
  - Fetch API for AJAX requests
  - DOM manipulation
  - Array methods (filter/sort)
  - Dynamic HTML generation
- **Bootstrap 5.3**: Responsive grid, Modals, Navbar, Carousel
- **Font Awesome 6.4**: Icons for UI elements
- **Google Fonts**: Roboto (Headings) & Open Sans (Body)

### Backend: 🆕
- **PHP 7.4+**: Server-side logic
- **MySQL**: Database management
- **PDO**: Secure database connections
- **Sessions**: Admin authentication
- **Email**: PHP mail() function with HTML templates

### Security Features: 🆕
- Password hashing (bcrypt)
- SQL injection prevention (prepared statements)
- XSS protection
- CSRF protection ready
- Session management
- Input validation & sanitization

## 📊 Database Schema

### Tables:
- **admins**: Admin user accounts
- **products**: Menu items with categories, prices, images
- **reservations**: Group bookings with customer details
- **reservation_items**: Preorder line items
- **contacts**: Contact form submissions
- **newsletter_subscribers**: Email list (prepared for future)

## 📧 Email Features

### Contact Form:
- Admin receives detailed inquiry with customer info
- Customer receives professional auto-reply confirmation
- All messages stored in database for tracking

### Reservations:
- Customer receives beautifully formatted email receipt with:
  - Reservation number
  - Date/time/guests information
  - Itemized preorder list
  - Total amount
  - Important instructions
- Admin receives notification with reservation details

## 🔒 Security Best Practices

1. **Change default admin password immediately**
2. **Use HTTPS in production**
3. **Regular database backups**
4. **Keep PHP and MySQL updated**
5. **Configure proper SMTP for email delivery**
6. **Review and limit file upload sizes**

## 📸 Key Features

### Customer Experience:
- Modern responsive homepage with carousel
- Interactive filterable menu
- Group reservation with preorder
- Email confirmation receipt

### Admin Dashboard:
- Login page with authentication
- Statistics overview dashboard
- Product management interface
- Reservation management system
- Contact messages inbox

## 🎯 Future Enhancements (Prepared)

- Newsletter subscription system (table ready)
- Customer reviews and ratings
- Online ordering with payment
- Customer accounts
- Loyalty program
- Analytics dashboard
- Multi-language support

## 🐛 Troubleshooting

### Email not working?
- Check PHP mail() configuration
- Consider PHPMailer for SMTP
- Verify spam folders

### Database errors?
- Check credentials in php/config.php
- Ensure MySQL is running
- Verify database was imported

### Admin can't login?
- Default: admin/admin123
- Check admins table in database
- Clear browser cache/cookies

## 📄 License

MIT License - See [LICENSE](LICENSE) file

## 👨‍💻 Project Info

Built as a web development project demonstrating:
- Modern responsive design
- Dynamic PHP backend
- Database integration
- Email automation
- Admin dashboard
- Security best practices

---

**Version:** 2.0 (PHP Backend Integrated)  
**Last Updated:** January 2026

