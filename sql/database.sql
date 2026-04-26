-- ============================================
-- DATABASE SCHEMA FOR LE CAFÉ LOCAL
-- MySQL/MariaDB Syntax (for XAMPP)
-- NOTE: VS Code may show errors if detecting as MSSQL - IGNORE THEM
-- This file is 100% correct for MySQL and will work perfectly in XAMPP
-- ============================================

CREATE DATABASE IF NOT EXISTS cafe_local CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cafe_local;

-- Admin users table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table (menu items)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category ENUM('boissons-chaudes', 'patisseries', 'sandwiches') NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    full_description TEXT NOT NULL,
    popularity INT DEFAULT 1,
    available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Reservations table
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    reservation_date DATE NOT NULL,
    reservation_time TIME NOT NULL,
    guests INT NOT NULL,
    total_amount DECIMAL(10, 2) DEFAULT 0.00,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Reservation items (preorders)
CREATE TABLE IF NOT EXISTS reservation_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Contact messages table
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Newsletter subscribers
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'unsubscribed') DEFAULT 'active'
);

-- Insert default admin (username: admin, password: admin123)
INSERT INTO admins (username, password, email) VALUES 
('admin', '$2y$10$Lho5zsk2w2nXfGbcCzcOVO8WP2JRiD/ZR1ZyY7nlJLpwCRreypRFq', 'admin@lecafelocal.tn');

-- Insert sample products
INSERT INTO products (name, category, price, image, description, full_description, popularity, available) VALUES
('Espresso', 'boissons-chaudes', 2.50, 'assets/images/espresso.png', 'Un espresso intense et aromatique', 'Notre espresso est préparé avec des grains de café Arabica soigneusement torréfiés pour un goût riche et équilibré.', 5, TRUE),
('Cappuccino', 'boissons-chaudes', 3.50, 'assets/images/cappuccino.png', 'Cappuccino crémeux avec de la mousse de lait', 'Un mélange parfait d\'espresso, de lait chaud et de mousse de lait onctueuse, saupoudré de cacao.', 4, TRUE),
('Croissant', 'patisseries', 2.00, 'assets/images/croissant.png', 'Croissant beurré et feuilleté', 'Nos croissants sont préparés quotidiennement avec du beurre AOP pour une texture légère et feuilletée.', 5, TRUE),
('Sandwich Jambon-Fromage', 'sandwiches', 5.50, 'assets/images/sandwich.png', 'Sandwich au jambon et fromage sur pain artisanal', 'Un sandwich gourmet préparé avec du jambon de qualité supérieure, du fromage emmental et notre pain artisanal.', 4, TRUE),
('Thé Vert', 'boissons-chaudes', 2.80, 'assets/images/the-vert.png', 'Thé vert rafraîchissant et détoxifiant', 'Notre thé vert est sélectionné pour ses propriétés antioxydantes et son goût délicat.', 3, TRUE);
