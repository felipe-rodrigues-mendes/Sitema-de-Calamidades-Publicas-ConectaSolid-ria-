<?php
session_start();
include("conexao.php");

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    $sql = "SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha, $usuario["senha"])) {
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["usuario_nome"] = $usuario["nome"];
            $_SESSION["usuario_tipo"] = $usuario["tipo"];

            header("Location: painel.php");
            exit;
        } else {
            $mensagem = "Senha incorreta.";
        }
    } else {
        $mensagem = "Usuário não encontrado.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>ConectaSolidária - Login</title>
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
        <a href="cadastro.php"><i class="fa fa-user"></i> Cadastro</a>
        <a href="login.php"><i class="fas fa-hand-holding-heart"></i> Doação</a>
        <a href="contato.php"><i class="fas fa-envelope"></i> Contato</a>
        <a href="sobre.php"><i class="fas fa-info-circle"></i> Sobre</a>
        
    </nav>
</header>

<main>
    <section class="form-section">
        <h2>Entrar no Sistema</h2>

        <?php if (!empty($mensagem)) : ?>
            <p><?php echo $mensagem; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" required>

            <label for="senha">Senha</label>
            <input type="password" name="senha" id="senha" required>

            <button type="submit">Entrar</button>
        </form>
    </section>
</main>

<footer>
    <p>© 2026 ConectaSolidária</p>
</footer>

</body>
</html>