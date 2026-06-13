

<aside class="sidebar" id="sidebar">

    <button id="toggleSidebar" class="menu-btn">☰</button>

    <h2 class="logo">
        CETI Wallet
    </h2>

    <p class="sidebar-user">
        <?= htmlspecialchars($_SESSION["nombre"]) ?>
    </p>

    <nav>

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="create_movement.php">
            Nuevo Movimiento
        </a>

        <a href="reports.php">
            Reportes
        </a>

        <a href="auth/logout.php">
            Cerrar Sesión
        </a>

    </nav>

</aside>