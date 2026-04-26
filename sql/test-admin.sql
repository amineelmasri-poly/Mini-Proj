-- Test database connection and admin user
-- Run this in phpMyAdmin SQL tab or MySQL command line

-- Check if database exists
SHOW DATABASES LIKE 'cafe_local';

-- Use the database
USE cafe_local;

-- Check if admin table exists and has data
SELECT * FROM admins;

-- If empty or password incorrect, delete and re-insert admin user:
DELETE FROM admins WHERE username = 'admin';

-- Insert admin with correct password (admin123)
INSERT INTO admins (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'amineelmasri@outlook.com');

-- Verify it was inserted
SELECT id, username, email, created_at FROM admins;
