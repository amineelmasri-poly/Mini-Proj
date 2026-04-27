<?php

require_once 'config.php';

header('Content-Type: application/json');
ini_set('display_errors', 0);

set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';
$guests = intval($_POST['guests'] ?? 0);
$preorders = json_decode($_POST['preorders'] ?? '{}', true);

$errors = [];

if (empty($name)) $errors[] = 'Le nom est requis';
if (empty($phone)) $errors[] = 'Le téléphone est requis';
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide';
if (empty($date)) $errors[] = 'La date est requise';
if (empty($time)) $errors[] = 'L\'heure est requise';
if ($guests < 7) $errors[] = 'Minimum 7 personnes pour une réservation de groupe';

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

$conn = null;

try {
    $conn = getDBConnection();
    $conn->beginTransaction();
    
    $totalAmount = 0;
    $preorderItems = [];
    
    if (!empty($preorders)) {
        foreach ($preorders as $productId => $quantity) {
            if ($quantity > 0) {
                $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
                $stmt->execute([$productId]);
                $product = $stmt->fetch();
                
                if ($product) {
                    $totalAmount += $product['price'] * $quantity;
                    $preorderItems[] = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'quantity' => $quantity
                    ];
                }
            }
        }
    }
    
    $stmt = $conn->prepare("
        INSERT INTO reservations (name, phone, email, reservation_date, reservation_time, guests, total_amount, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    $stmt->execute([$name, $phone, $email, $date, $time, $guests, $totalAmount]);
    $reservationId = $conn->lastInsertId();
    
    if (!empty($preorderItems)) {
        $stmt = $conn->prepare("
            INSERT INTO reservation_items (reservation_id, product_id, quantity, price) 
            VALUES (?, ?, ?, ?)
        ");
        
        foreach ($preorderItems as $item) {
            $stmt->execute([$reservationId, $item['id'], $item['quantity'], $item['price']]);
        }
    }
    
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Votre réservation a été enregistrée avec succès.',
        'reservationId' => $reservationId,
        'receipt' => [
            'name' => $name,
            'email' => $email,
            'date' => $date,
            'time' => $time,
            'guests' => $guests,
            'items' => $preorderItems,
            'total' => $totalAmount
        ]
    ]);
    
} catch(Throwable $e) {
    if ($conn instanceof PDO && $conn->inTransaction()) {
        $conn->rollBack();
    }
    error_log('Reservation handler error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la réservation. Veuillez réessayer.'
    ]);
} finally {
    restore_error_handler();
}
?>
