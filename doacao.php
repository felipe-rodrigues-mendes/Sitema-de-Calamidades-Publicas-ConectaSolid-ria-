<?php
$cidadeSelecionada = isset($_GET['cidade']) ? htmlspecialchars($_GET['cidade']) : "";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = htmlspecialchars($_POST["nome"]);
    $email = htmlspecialchars($_POST["email"]);
    $cidade = htmlspecialchars($_POST["cidade"]);
    $item = htmlspecialchars($_POST["item"]);
    $quantidade = htmlspecialchars($_POST["quantidade"]);

    $mensagem = "Doação registrada com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Doação</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>

<header>
<div class="logo-container">
    <a href="index.php">
        <img src="images/logo.png" class="logo">
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

<main class="container mt-4">

<h2></i> Fazer Doação</h2>

<?php if ($mensagem != ""): ?>
    <div class="alert alert-success">
        <?php echo $mensagem; ?>
    </div>
<?php endif; ?>

<form method="POST">

<label>Nome</label>
<input type="text" name="nome" class="form-control" required>

<label>Email</label>
<input type="email" name="email" class="form-control" required>

<label>Cidade</label>
<input type="text" name="cidade" class="form-control" 
value="<?php echo $cidadeSelecionada; ?>" readonly>

<label>Item para doação</label>
<input type="text" name="item" class="form-control" required>

<label>Quantidade</label>
<input type="number" name="quantidade" class="form-control" required>

<br>

<button type="submit" class="btn btn-success">
    <i class="fas fa-hand-holding-heart"></i> Confirmar Doação
</button>

</form>

</main>

<footer class="text-center mt-4">
<p>© 2026 Conecta Solidária</p>
</footer>

</body>
</html>