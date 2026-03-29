<?php
// ARRAY DAS CIDADES E NECESSIDADES (ATUALIZADO)
$cidades = [
    [
        "nome" => "Brasil",
        "necessidades" => [
            "Remédios",
            "Roupas de cama e banho",
            "Kits de higiene",
            "Fraldas descartáveis",
            "Roupas íntimas novas",
            "Calçados fechados",
            "Material de construção",
            "Ferramentas",
            "Água potável",
            "Alimentos não perecíveis",
            "Colchões e cobertores",
            "Materiais de limpeza",
            "Roupas e agasalhos",
            "Produtos de higiene",
            "Ração",
            "Caixas de transporte para animais"
        ]
    ]
];

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST["nome"];
    $cpf = $_POST["cpf"]; // ✅ corrigido
    $email = $_POST["email"];
    $cidade = $_POST["cidade"];
    $necessidade = $_POST["necessidade"];
    $descricao = $_POST["descricao"];

    echo "<h3>Cadastro realizado com sucesso!</h3>";
    echo "Nome: $nome <br>";
    echo "CPF: $cpf <br>";
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

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="css/style.css">

<style>
/* 🔥 Adiciona * vermelho automaticamente */
label::after {
    content: " *";
    color: red;
}
</style>

</head>

<body>

<header>
<div class="logo-container">
    <a href="index.php">
        <img src="images/logo.png" class="logo">
    </a>
</div>

<nav>
    <a href="index.php">Início</a>
    <a href="cadastro.php" class="active">Cadastro</a>
    <a href="contato.php">Contato</a>
    <a href="sobre.php">Sobre</a>
</nav>
</header>

<main>

<h2><strong>Cadastro</strong></h2>

<form method="POST" action="">

<label>Nome</label>
<input type="text" name="nome" required>

<label>CPF</label>
<input type="text" name="cpf" required>

<label>Email</label>
<input type="email" name="email" required>

<label>Cidade</label>
<input type="text" name="cidade" required>

<label>Necessidade</label>
<select name="necessidade" required>

<?php
foreach ($cidades as $cidade) {
    foreach ($cidade["necessidades"] as $item) {
        echo "<option value='$item'>$item</option>";
    }
}
?>

</select>

<label>Descrição</label>
<textarea name="descricao"></textarea>

<br>
<button type="submit" class="btn btn-primary">Cadastrar</button>

</form>

</main>

<footer>
<p>© 2026 Conecta Solidária</p>
</footer>

</body>
</html>
