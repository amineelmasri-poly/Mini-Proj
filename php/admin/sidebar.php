<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
                    <i class="fas fa-chart-line me-2"></i> Tableau de bord
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'reservations.php' ? 'active' : '' ?>" href="reservations.php">
                    <i class="fas fa-calendar-alt me-2"></i> Réservations
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'menu_management.php' ? 'active' : '' ?>" href="menu_management.php">
                    <i class="fas fa-utensils me-2"></i> Menu
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'contacts.php' ? 'active' : '' ?>" href="contacts.php">
                    <i class="fas fa-envelope me-2"></i> Messages
                </a>
            </li>
        </ul>
        
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Actions rapides</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" href="../../index.html" target="_blank">
                    <i class="fas fa-external-link-alt me-2"></i> Voir le site
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                </a>
            </li>
        </ul>
    </div>
</nav>
