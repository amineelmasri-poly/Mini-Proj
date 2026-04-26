<?php
require_once '../config.php';

// Protect admin pages: only logged-in admins can continue.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../../index.html');
    exit;
}

$adminUsername = $_SESSION['admin_username'] ?? 'Admin';
$adminEmail = $_SESSION['admin_email'] ?? '';
?>
