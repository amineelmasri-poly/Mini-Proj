-- ============================================
-- FIX ADMIN PASSWORD
-- ============================================
-- This will reset the admin password to: admin123
-- Run this in phpMyAdmin to fix login issues

USE cafe_local;

-- Update admin password to the correct hash for 'admin123'
UPDATE admins 
SET password = '$2y$10$Lho5zsk2w2nXfGbcCzcOVO8WP2JRiD/ZR1ZyY7nlJLpwCRreypRFq' 
WHERE username = 'admin';

-- Verify the update
SELECT id, username, email, created_at, 
       'Password updated successfully' as status 
FROM admins 
WHERE username = 'admin';
