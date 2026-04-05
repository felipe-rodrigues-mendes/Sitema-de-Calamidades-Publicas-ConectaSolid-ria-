<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
    <div class="logo-container">
        <a href="index.php">
            <img src="images/logo.png" class="logo" alt="Logo Conecta Solidária">
        </a>
    </div>

    <nav>
        <a href="index.php"><i class="fas fa-home"></i> Início</a>

        <?php if (isset($_SESSION["usuario_id"])) : ?>
            <a href="painel.php"><i class="fas fa-user-circle"></i> Painel</a>
            <a href="doacao.php"><i class="fas fa-hand-holding-heart"></i> Doação</a>
            <a href="contato.php"><i class="fas fa-envelope"></i> Contato</a>
            <a href="sobre.php"><i class="fas fa-info-circle"></i> Sobre</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
        <?php else : ?>
            <a href="cadastro.php"><i class="fa fa-user"></i> Cadastro</a>
            <a href="login.php"><i class="fas fa-hand-holding-heart"></i> doação</a>
            <a href="contato.php"><i class="fas fa-envelope"></i> Contato</a>
            <a href="sobre.php"><i class="fas fa-info-circle"></i> Sobre</a>
        <?php endif; ?>
    </nav>
</header>