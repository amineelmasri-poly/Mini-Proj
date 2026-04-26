<?php
require_once 'auth_check.php';

$conn = getDBConnection();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['id'];
    
    try {
        if ($action === 'mark_read') {
            $stmt = $conn->prepare("UPDATE contacts SET status = 'read' WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Message marqué comme lu!";
        } elseif ($action === 'mark_replied') {
            $stmt = $conn->prepare("UPDATE contacts SET status = 'replied' WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Message marqué comme répondu!";
        } elseif ($action === 'delete') {
            $stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Message supprimé!";
        }
    } catch(Exception $e) {
        $message = "Erreur: " . $e->getMessage();
    }
}

$filter = $_GET['status'] ?? 'all';

$sql = "SELECT * FROM contacts WHERE 1=1";
if ($filter !== 'all') {
    $sql .= " AND status = '$filter'";
}
$sql .= " ORDER BY created_at DESC";

$contacts = $conn->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Le Café Local</title>
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
                    <h1 class="h2"><i class="fas fa-envelope me-2"></i> Messages de contact</h1>
                    <div class="btn-group">
                        <a href="?status=all" class="btn btn-sm btn-outline-secondary <?= $filter === 'all' ? 'active' : '' ?>">Tous</a>
                        <a href="?status=new" class="btn btn-sm btn-outline-success <?= $filter === 'new' ? 'active' : '' ?>">Nouveaux</a>
                        <a href="?status=read" class="btn btn-sm btn-outline-info <?= $filter === 'read' ? 'active' : '' ?>">Lus</a>
                        <a href="?status=replied" class="btn btn-sm btn-outline-primary <?= $filter === 'replied' ? 'active' : '' ?>">Répondus</a>
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
                        <?php if (empty($contacts)): ?>
                            <p class="text-center text-muted">Aucun message trouvé</p>
                        <?php else: ?>
                            <div class="list-group">
                                <?php foreach ($contacts as $contact): ?>
                                    <div class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1">
                                                        <i class="fas fa-user me-2"></i><?= htmlspecialchars($contact['name']) ?>
                                                        <span class="badge bg-<?= ['new'=>'success','read'=>'info','replied'=>'primary'][$contact['status']] ?> ms-2">
                                                            <?= ['new'=>'Nouveau','read'=>'Lu','replied'=>'Répondu'][$contact['status']] ?>
                                                        </span>
                                                    </h6>
                                                    <small class="text-muted"><?= date('d/m/Y à H:i', strtotime($contact['created_at'])) ?></small>
                                                </div>
                                                <p class="mb-1"><i class="fas fa-envelope me-2"></i><?= htmlspecialchars($contact['email']) ?></p>
                                                <p class="mb-0 mt-2"><?= nl2br(htmlspecialchars($contact['message'])) ?></p>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <a href="mailto:<?= htmlspecialchars($contact['email']) ?>" class="btn btn-sm btn-primary mb-1 w-100">
                                                    <i class="fas fa-reply me-2"></i>Répondre
                                                </a>
                                                <?php if ($contact['status'] === 'new'): ?>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="action" value="mark_read">
                                                        <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-info mb-1 w-100">
                                                            <i class="fas fa-eye me-2"></i>Marquer lu
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                <?php if ($contact['status'] !== 'replied'): ?>
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="action" value="mark_replied">
                                                        <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-success mb-1 w-100">
                                                            <i class="fas fa-check me-2"></i>Répondu
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                <form method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce message?')">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                                        <i class="fas fa-trash me-2"></i>Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
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
