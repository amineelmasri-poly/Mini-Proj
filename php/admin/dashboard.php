<?php
require_once 'auth_check.php';

$conn = getDBConnection();

// statistics
$stats = [
    'reservations_today' => 0,
    'reservations_pending' => 0,
    'contacts_new' => 0,
    'total_products' => 0,
    'revenue_today' => 0
];

try {
    $stmt = $conn->query("SELECT COUNT(*) as count FROM reservations WHERE DATE(created_at) = CURDATE()");
    $stats['reservations_today'] = $stmt->fetch()['count'];
    
    $stmt = $conn->query("SELECT COUNT(*) as count FROM reservations WHERE status = 'pending'");
    $stats['reservations_pending'] = $stmt->fetch()['count'];
    
    $stmt = $conn->query("SELECT COUNT(*) as count FROM contacts WHERE status = 'new'");
    $stats['contacts_new'] = $stmt->fetch()['count'];
    
    $stmt = $conn->query("SELECT COUNT(*) as count FROM products WHERE available = 1");
    $stats['total_products'] = $stmt->fetch()['count'];
    
    $stmt = $conn->query("SELECT COALESCE(SUM(total_amount), 0) as revenue FROM reservations WHERE DATE(created_at) = CURDATE()");
    $stats['revenue_today'] = $stmt->fetch()['revenue'];
    
    $stmt = $conn->query("SELECT * FROM reservations ORDER BY created_at DESC LIMIT 5");
    $recentReservations = $stmt->fetchAll();
    
    $stmt = $conn->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5");
    $recentContacts = $stmt->fetchAll();
    
} catch(Exception $e) {
    $error = "Erreur lors du chargement des données";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Le Café Local</title>
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
                    <h1 class="h2"><i class="fas fa-chart-line me-2"></i> Tableau de bord</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-calendar-day"></i> Aujourd'hui
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card stat-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="stat-label">Réservations aujourd'hui</p>
                                        <h3 class="stat-value"><?= $stats['reservations_today'] ?></h3>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card stat-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="stat-label">En attente</p>
                                        <h3 class="stat-value"><?= $stats['reservations_pending'] ?></h3>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card stat-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="stat-label">Nouveaux messages</p>
                                        <h3 class="stat-value"><?= $stats['contacts_new'] ?></h3>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card stat-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="stat-label">Revenu aujourd'hui</p>
                                        <h3 class="stat-value"><?= number_format($stats['revenue_today'], 2) ?> TND</h3>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Reserv -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-calendar me-2"></i> Réservations récentes</h5>
                        <a href="reservations.php" class="btn btn-sm btn-outline-primary">Voir tout</a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recentReservations)): ?>
                            <p class="text-muted text-center">Aucune réservation</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Client</th>
                                            <th>Date/Heure</th>
                                            <th>Invités</th>
                                            <th>Total</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentReservations as $res): ?>
                                            <tr>
                                                <td>#<?= $res['id'] ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($res['name']) ?></strong><br>
                                                    <small class="text-muted"><?= htmlspecialchars($res['email']) ?></small>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($res['reservation_date'])) ?> à <?= date('H:i', strtotime($res['reservation_time'])) ?></td>
                                                <td><?= $res['guests'] ?> pers.</td>
                                                <td><?= number_format($res['total_amount'], 2) ?> TND</td>
                                                <td>
                                                    <?php
                                                    $statusClass = [
                                                        'pending' => 'warning',
                                                        'confirmed' => 'success',
                                                        'cancelled' => 'danger'
                                                    ];
                                                    $statusLabel = [
                                                        'pending' => 'En attente',
                                                        'confirmed' => 'Confirmée',
                                                        'cancelled' => 'Annulée'
                                                    ];
                                                    ?>
                                                    <span class="badge bg-<?= $statusClass[$res['status']] ?>">
                                                        <?= $statusLabel[$res['status']] ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Recent Contacts -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-envelope me-2"></i> Messages récents</h5>
                        <a href="contacts.php" class="btn btn-sm btn-outline-primary">Voir tout</a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recentContacts)): ?>
                            <p class="text-muted text-center">Aucun message</p>
                        <?php else: ?>
                            <div class="list-group">
                                <?php foreach ($recentContacts as $contact): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?= htmlspecialchars($contact['name']) ?></h6>
                                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($contact['created_at'])) ?></small>
                                        </div>
                                        <p class="mb-1"><?= substr(htmlspecialchars($contact['message']), 0, 100) ?>...</p>
                                        <small class="text-muted"><?= htmlspecialchars($contact['email']) ?></small>
                                        <span class="badge bg-<?= $contact['status'] === 'new' ? 'success' : 'secondary' ?> ms-2">
                                            <?= $contact['status'] === 'new' ? 'Nouveau' : 'Lu' ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
