<?php
// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Pegando os dados do formulário
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $cidade = $_POST["cidade"];
    $necessidade = $_POST["necessidade"];
    $descricao = $_POST["descricao"];

    // Exemplo simples (mostrar os dados na tela)
    echo "<h3>Cadastro realizado com sucesso!</h3>";
    echo "Nome: $nome <br>";
    echo "Email: $email <br>";
    echo "Cidade: $cidade <br>";
    echo "Necessidade: $necessidade <br>";
    echo "Descrição: $descricao <br><hr>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Cadastro</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<header>
    
<div class="logo-container">
    <a href="index.php">
        <img src="images/logo.png" class="logo">
    </a>
    <h1></h1>
</div>

<nav>
    <a href="index.php">Início</a>
    <a href="cadastro.php" class="active">Cadastro</a>
    <a href="contato.php">Contato</a>
    <a href="sobre.php" class="active">Sobre</a>
</nav>

</header>

<main>

<h2>Cadastro</h2>

<form method="POST" action="">

<label>Nome</label>
<input type="text" name="nome" required>

<label>Email</label>
<input type="email" name="email" required>

<label>Cidade</label>
<input type="text" name="cidade" required>

<label>Necessidade</label>
<select name="necessidade">
<option>Alimentos</option>
<option>Água</option>
<option>Roupas</option>
<option>Higiene</option>
<option>Medicamentos</option>
</select>

<label>Descrição</label>
<textarea name="descricao"></textarea>

<button type="submit">Cadastrar</button>

</form>

</main>

<footer>
<p>© 2026 Conecta Solidária</p>
</footer>

</body>
</html>
