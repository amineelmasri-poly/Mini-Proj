<?php
require_once '../config.php';

header('Content-Type: application/json');

try {
    $conn = getDBConnection();
    
    // filter
    $category = $_GET['category'] ?? 'all';
    $available = $_GET['available'] ?? 'true';
    
    $sql = "SELECT id, name, category, price, image, description, full_description, popularity, available 
            FROM products WHERE 1=1";
    $params = [];
    
    if ($category !== 'all') {
        $sql .= " AND category = ?";
        $params[] = $category;
    }
    
    if ($available === 'true') {
        $sql .= " AND available = 1";
    }
    
    $sql .= " ORDER BY popularity DESC, name ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
    
    // Format
    foreach ($products as &$product) {
        $product['price'] = floatval($product['price']);
        $product['popularity'] = intval($product['popularity']);
        $product['available'] = boolval($product['available']);
    }
    
    echo json_encode([
        'success' => true,
        'products' => $products
    ]);
    
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la récupération des produits'
    ]);
}
?>
