<?php
require_once '../config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../../index.html');
    exit;
}

$adminUsername = $_SESSION['admin_username'] ?? 'Admin';
$adminEmail = $_SESSION['admin_email'] ?? '';
?>
