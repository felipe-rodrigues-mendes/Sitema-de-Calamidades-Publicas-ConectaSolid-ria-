<?php
include("conexao.php");

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $cpf = trim($_POST["cpf"]);
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $verifica = "SELECT id FROM usuarios WHERE email = ? OR cpf = ?";
    $stmtVerifica = $conn->prepare($verifica);
    $stmtVerifica->bind_param("ss", $email, $cpf);
    $stmtVerifica->execute();
    $resultadoVerifica = $stmtVerifica->get_result();

    if ($resultadoVerifica->num_rows > 0) {
        $mensagem = "Já existe um usuário com esse e-mail ou CPF.";
    } else {
        $sql = "INSERT INTO usuarios (nome, cpf, email, senha, tipo) VALUES (?, ?, ?, ?, 'doador')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nome, $cpf, $email, $senhaHash);

        if ($stmt->execute()) {
            header("Location: login.php?cadastro=sucesso");
            exit();
        } else {
            $mensagem = "Erro ao cadastrar usuário.";
        }

        $stmt->close();
    }

    $stmtVerifica->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>ConectaSolidária</title>

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
    <a href="doacao.php"><i class="fas fa-hand-holding-heart"></i> Doação</a>
    <a href="contato.php"><i class="fas fa-envelope"></i> Contato</a> 
    <a href="sobre.php"><i class="fas fa-info-circle"></i> Sobre</a> 
</nav>

</header>

<main>
    <section class="form-section">
        <h2>Cadastro de Doador</h2>

        <?php if (!empty($mensagem)) : ?>
            <p><?php echo htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="nome">Nome completo</label>
            <input type="text" name="nome" id="nome" required>

            <label for="cpf">CPF</label>
            <input type="text" name="cpf" id="cpf" required>

            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" required>

            <label for="senha">Senha</label>
            <input type="password" name="senha" id="senha" required>

            <button type="submit">Cadastrar</button>
        </form>
    </section>
</main>

<footer>
    <p>© 2026 ConectaSolidária</p>
</footer>

</body>
</html>
