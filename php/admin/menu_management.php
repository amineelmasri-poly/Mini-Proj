<?php
require_once 'auth_check.php';

$conn = getDBConnection();
$message = '';
$messageType = '';

//product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        if ($action === 'add' || $action === 'edit') {
            $id = $_POST['id'] ?? null;
            $name = trim($_POST['name']);
            $category = $_POST['category'];
            $price = floatval($_POST['price']);
            $description = trim($_POST['description']);
            $fullDescription = trim($_POST['full_description']);
            $popularity = intval($_POST['popularity']);
            $available = isset($_POST['available']) ? 1 : 0;
            
            // image 
            $imagePath = $_POST['existing_image'] ?? 'assets/images/';
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../../assets/images/';
                $fileName = basename($_FILES['image']['name']);
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imagePath = 'assets/images/' . $fileName;
                }
            }
            
            if ($action === 'add') {
                $stmt = $conn->prepare("
                    INSERT INTO products (name, category, price, image, description, full_description, popularity, available)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$name, $category, $price, $imagePath, $description, $fullDescription, $popularity, $available]);
                $message = "Produit ajouté avec succès!";
            } else {
                $stmt = $conn->prepare("
                    UPDATE products 
                    SET name = ?, category = ?, price = ?, image = ?, description = ?, full_description = ?, popularity = ?, available = ?
                    WHERE id = ?
                ");
                $stmt->execute([$name, $category, $price, $imagePath, $description, $fullDescription, $popularity, $available, $id]);
                $message = "Produit modifié avec succès!";
            }
            $messageType = 'success';
        }
        
        if ($action === 'delete') {
            $id = $_POST['id'];
            $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Produit supprimé avec succès!";
            $messageType = 'success';
        }
        
        if ($action === 'toggle') {
            $id = $_POST['id'];
            $stmt = $conn->prepare("UPDATE products SET available = NOT available WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Statut modifié avec succès!";
            $messageType = 'success';
        }
    } catch(Exception $e) {
        $message = "Erreur: " . $e->getMessage();
        $messageType = 'danger';
    }
}

//  all products get 
$products = $conn->query("SELECT * FROM products ORDER BY category, name")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du Menu - Le Café Local</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="fas fa-utensils me-2"></i> Gestion du Menu</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal" onclick="resetForm()">
                        <i class="fas fa-plus me-2"></i> Ajouter un produit
                    </button>
                </div>
                
                <?php if ($message): ?>
                    <div class="alert alert-<?= $messageType ?> alert-dismissible fade show">
                        <?= $message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Products Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Nom</th>
                                        <th>Catégorie</th>
                                        <th>Prix</th>
                                        <th>Popularité</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td>
                                                <img src="../../<?= htmlspecialchars($product['image']) ?>" 
                                                     alt="<?= htmlspecialchars($product['name']) ?>" 
                                                     class="product-thumbnail">
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($product['name']) ?></strong><br>
                                                <small class="text-muted"><?= htmlspecialchars($product['description']) ?></small>
                                            </td>
                                            <td>
                                                <?php
                                                $categoryLabels = [
                                                    'boissons-chaudes' => 'Boissons Chaudes',
                                                    'patisseries' => 'Pâtisseries',
                                                    'sandwiches' => 'Sandwiches'
                                                ];
                                                ?>
                                                <span class="badge bg-secondary"><?= $categoryLabels[$product['category']] ?></span>
                                            </td>
                                            <td><strong><?= number_format($product['price'], 2) ?> TND</strong></td>
                                            <td>
                                                <?php for ($i = 0; $i < 5; $i++): ?>
                                                    <i class="fas fa-star <?= $i < $product['popularity'] ? 'text-warning' : 'text-muted' ?>"></i>
                                                <?php endfor; ?>
                                            </td>
                                            <td>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="action" value="toggle">
                                                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-<?= $product['available'] ? 'success' : 'secondary' ?>">
                                                        <?= $product['available'] ? 'Disponible' : 'Indisponible' ?>
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" onclick="editProduct(<?= htmlspecialchars(json_encode($product)) ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit?')">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Prod Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Ajouter un produit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" id="formAction" value="add">
                        <input type="hidden" name="id" id="productId">
                        <input type="hidden" name="existing_image" id="existingImage">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nom</label>
                                <input type="text" class="form-control" name="name" id="productName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Catégorie</label>
                                <select class="form-select" name="category" id="productCategory" required>
                                    <option value="boissons-chaudes">Boissons Chaudes</option>
                                    <option value="patisseries">Pâtisseries</option>
                                    <option value="sandwiches">Sandwiches</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Prix (TND)</label>
                                <input type="number" class="form-control" name="price" id="productPrice" step="0.01" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Popularité (1-5)</label>
                                <input type="number" class="form-control" name="popularity" id="productPopularity" min="1" max="5" value="3">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <small class="text-muted">Laisser vide pour conserver l'image actuelle</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description courte</label>
                            <input type="text" class="form-control" name="description" id="productDescription" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description complète</label>
                            <textarea class="form-control" name="full_description" id="productFullDescription" rows="3" required></textarea>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="available" id="productAvailable" checked>
                            <label class="form-check-label" for="productAvailable">
                                Produit disponible
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function resetForm() {
            document.getElementById('modalTitle').textContent = 'Ajouter un produit';
            document.getElementById('formAction').value = 'add';
            document.getElementById('productId').value = '';
            document.querySelector('#productModal form').reset();
        }
        
        function editProduct(product) {
            document.getElementById('modalTitle').textContent = 'Modifier le produit';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('productId').value = product.id;
            document.getElementById('productName').value = product.name;
            document.getElementById('productCategory').value = product.category;
            document.getElementById('productPrice').value = product.price;
            document.getElementById('productPopularity').value = product.popularity;
            document.getElementById('productDescription').value = product.description;
            document.getElementById('productFullDescription').value = product.full_description;
            document.getElementById('productAvailable').checked = product.available == 1;
            document.getElementById('existingImage').value = product.image;
            
            new bootstrap.Modal(document.getElementById('productModal')).show();
        }
    </script>
</body>
</html>
