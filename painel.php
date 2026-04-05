<?php
include("proteger.php");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>ConectaSolidária - Painel</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header>
    <div class="logo-container">
        <a href="index.php">
            <img src="images/logo.png" class="logo" alt="Logo Conecta Solidária">
        </a>
    </div>

    <nav>
        <a href="index.php"><i class="fas fa-home"></i> Início</a>
        <a href="painel.php"><i class="fas fa-user-circle"></i> Painel</a>
        <a href="doacao.php"><i class="fas fa-hand-holding-heart"></i> Doação</a>
        <a href="contato.php"><i class="fas fa-envelope"></i> Contato</a>
        <a href="sobre.php"><i class="fas fa-info-circle"></i> Sobre</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </nav>
</header>

<main>
    <section class="form-section">
        <h2>Bem-vindo ao Painel do doador</h2>
        <p>Olá doador, <strong><?php echo htmlspecialchars($_SESSION["usuario_nome"]); ?></strong>!</p>
        <p>Você está logado no sistema com o perfil: <strong><?php echo htmlspecialchars($_SESSION["usuario_tipo"]); ?></strong>.</p>

        <div class="card">
            <h3>Seus dados</h3>
            <p><strong>ID:</strong> <?php echo htmlspecialchars($_SESSION["usuario_id"]); ?></p>
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($_SESSION["usuario_nome"]); ?></p>
            <p><strong>Tipo:</strong> <?php echo htmlspecialchars($_SESSION["usuario_tipo"]); ?></p>
        </div>
    </section>
</main>

<footer>
    <p>© 2026 ConectaSolidária</p>
</footer>

</body>
</html>