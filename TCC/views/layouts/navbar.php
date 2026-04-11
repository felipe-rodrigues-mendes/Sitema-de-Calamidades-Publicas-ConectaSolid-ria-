<?php
// Navbar compartilhada - refatorada de menu.php
// Esta navbar é incluída em todas as páginas via layout base
SessionManager::start();
$isAuthenticated = SessionManager::isAuthenticated();
$isAdmin = SessionManager::isAdmin();
$userName = SessionManager::getUserName();
?>

<header>
    <div class="logo-container">
        <a href="index.php">
            <img src="assets/uploas/logo.PNG" class="logo" alt="Logo ConectaSolidária">
        </a>
    </div>

    <nav>
        <a href="index.php"><i class="fas fa-home"></i> Início</a>

        <?php if (!$isAuthenticated): ?>
            <a href="index.php?page=register"><i class="fa fa-user"></i> Cadastro</a>
            <a href="index.php?page=login"><i class="fas fa-sign-in-alt"></i> Login</a>
        <?php endif; ?>

        <a href="index.php?page=collection_points"><i class="fas fa-location-dot"></i> Pontos de Coleta</a>
        <a href="index.php?page=contact"><i class="fas fa-envelope"></i> Contato</a>
        <a href="index.php?page=about"><i class="fas fa-info-circle"></i> Sobre</a>

        <?php if ($isAuthenticated): ?>
            <a href="index.php?page=dashboard"><i class="fas fa-user-circle"></i> Painel</a>
            <a href="index.php?page=donation_create"><i class="fas fa-hand-holding-heart"></i> Fazer Doação</a>

            <?php if ($isAdmin): ?>
                <a href="index.php?page=admin_donations"><i class="fas fa-user-shield"></i> Admin</a>
                <a href="index.php?page=admin_inventory"><i class="fas fa-boxes-stacked"></i> Estoque</a>
                <a href="index.php?page=admin_distributions"><i class="fas fa-truck"></i> Distribuições</a>
            <?php endif; ?>

            <a href="index.php?page=logout" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Sair</a>
        <?php endif; ?>
    </nav>
</header>

<style>
    .btn-logout {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        color: white !important;
        padding: 8px 16px !important;
        border-radius: 8px;
        text-decoration: none;
        transition: 0.3s ease;
    }

    .btn-logout:hover {
        color: white !important;
        filter: brightness(1.1);
    }
</style>

