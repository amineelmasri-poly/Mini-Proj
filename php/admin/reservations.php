<?php
require_once 'auth_check.php';

$conn = getDBConnection();
$message = '';

// status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = $_POST['id'];
    
    try {
        if ($action === 'confirm') {
            $stmt = $conn->prepare("UPDATE reservations SET status = 'confirmed' WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Réservation confirmée!";
        } elseif ($action === 'cancel') {
            $stmt = $conn->prepare("UPDATE reservations SET status = 'cancelled' WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Réservation annulée!";
        } elseif ($action === 'delete') {
            $stmt = $conn->prepare("DELETE FROM reservations WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Réservation supprimée!";
        }
    } catch(Exception $e) {
        $message = "Erreur: " . $e->getMessage();
    }
}

// filter get
$filter = $_GET['status'] ?? 'all';

// query
$sql = "SELECT * FROM reservations WHERE 1=1";
if ($filter !== 'all') {
    $sql .= " AND status = '$filter'";
}
$sql .= " ORDER BY reservation_date DESC, reservation_time DESC";

$reservations = $conn->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservations - Le Café Local</title>
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
                    <h1 class="h2"><i class="fas fa-calendar-alt me-2"></i> Réservations</h1>
                    <div class="btn-group">
                        <a href="?status=all" class="btn btn-sm btn-outline-secondary <?= $filter === 'all' ? 'active' : '' ?>">Toutes</a>
                        <a href="?status=pending" class="btn btn-sm btn-outline-warning <?= $filter === 'pending' ? 'active' : '' ?>">En attente</a>
                        <a href="?status=confirmed" class="btn btn-sm btn-outline-success <?= $filter === 'confirmed' ? 'active' : '' ?>">Confirmées</a>
                        <a href="?status=cancelled" class="btn btn-sm btn-outline-danger <?= $filter === 'cancelled' ? 'active' : '' ?>">Annulées</a>
                    </div>
                </div>
                
                <?php if ($message): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                        <?php if (empty($reservations)): ?>
                            <p class="text-center text-muted">Aucune réservation trouvée</p>
                        <?php else: ?>
                            <?php foreach ($reservations as $res): ?>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h5 class="card-title">
                                                    Réservation #<?= $res['id'] ?>
                                                    <span class="badge bg-<?= ['pending'=>'warning','confirmed'=>'success','cancelled'=>'danger'][$res['status']] ?>">
                                                        <?= ['pending'=>'En attente','confirmed'=>'Confirmée','cancelled'=>'Annulée'][$res['status']] ?>
                                                    </span>
                                                </h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><i class="fas fa-user me-2"></i><strong><?= htmlspecialchars($res['name']) ?></strong></p>
                                                        <p class="mb-1"><i class="fas fa-envelope me-2"></i><?= htmlspecialchars($res['email']) ?></p>
                                                        <p class="mb-1"><i class="fas fa-phone me-2"></i><?= htmlspecialchars($res['phone']) ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><i class="fas fa-calendar me-2"></i><?= date('d/m/Y', strtotime($res['reservation_date'])) ?></p>
                                                        <p class="mb-1"><i class="fas fa-clock me-2"></i><?= date('H:i', strtotime($res['reservation_time'])) ?></p>
                                                        <p class="mb-1"><i class="fas fa-users me-2"></i><?= $res['guests'] ?> personnes</p>
                                                        <p class="mb-1"><i class="fas fa-money-bill-wave me-2"></i><?= number_format($res['total_amount'], 2) ?> TND</p>
                                                    </div>
                                                </div>
                                                <?php
                                                // Get reservation
                                                $stmt = $conn->prepare("
                                                    SELECT ri.*, p.name 
                                                    FROM reservation_items ri
                                                    JOIN products p ON ri.product_id = p.id
                                                    WHERE ri.reservation_id = ?
                                                ");
                                                $stmt->execute([$res['id']]);
                                                $items = $stmt->fetchAll();
                                                
                                                if (!empty($items)):
                                                ?>
                                                    <hr>
                                                    <h6>Précommande:</h6>
                                                    <ul class="small">
                                                        <?php foreach ($items as $item): ?>
                                                            <li><?= $item['quantity'] ?>x <?= htmlspecialchars($item['name']) ?> (<?= number_format($item['price'] * $item['quantity'], 2) ?> TND)</li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <small class="text-muted d-block mb-3">Créée le <?= date('d/m/Y à H:i', strtotime($res['created_at'])) ?></small>
                                                
                                                <?php if ($res['status'] === 'pending'): ?>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="action" value="confirm">
                                                        <input type="hidden" name="id" value="<?= $res['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-success mb-2 w-100">
                                                            <i class="fas fa-check me-2"></i>Confirmer
                                                        </button>
                                                    </form>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="action" value="cancel">
                                                        <input type="hidden" name="id" value="<?= $res['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-warning mb-2 w-100">
                                                            <i class="fas fa-times me-2"></i>Annuler
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                
                                                <form method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette réservation?')">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?= $res['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                                        <i class="fas fa-trash me-2"></i>Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
