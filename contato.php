<?php
$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = htmlspecialchars($_POST["nome"]);
    $email = htmlspecialchars($_POST["email"]);
    $mensagemTexto = htmlspecialchars($_POST["mensagem"]);

    $mensagem = "Mensagem enviada com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Contato</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- CSS -->
<link rel="stylesheet" href="css/style.css">

<!-- ✅ Font Awesome (FALTAVA ISSO) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
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

<!-- ✅ NAV ATUALIZADA -->
<nav>
    <a href="index.php"><i class="fas fa-home"></i> Início</a> 
    <a href="cadastro.php"><i class="fa fa-user"></i> Cadastro</a> 
     <a href="doação"><i class="fas fa-hand-holding-heart"></i> Doação</a>
    <a href="contato.php"><i class="fas fa-envelope"></i> Contato</a> 
    <a href="sobre.php"><i class="fas fa-info-circle"></i> Sobre</a> 
</nav>

</header>

<main class="main-content">

<h2><strong><i class="fas fa-envelope"></i> Entre em Contato</strong></h2>
<p>Veja onde nos encontrar:</p>

<p><strong>Endereço:</strong> St. N, Área Especial QNN 14 - Ceilândia, Brasília - DF</p>
<p><strong>CEP:</strong> 72220-140</p>
<p><strong>Telefone:</strong> (61) 3345-8714</p>
<p><strong>Email:</strong> conectasolidaria@gmail.com</p>

<div class="info-item">
<h3><i class="fas fa-clock"></i> Horário de Funcionamento:</h3>
<p>Segunda a Sábado: 7:00 às 22:00 (Presencial)</p>
<p>Domingo: 7:00 às 22:00 (Online)</p>
</div>

<!-- MENSAGEM -->
<?php if ($mensagem != ""): ?>
    <p style="color: green; font-weight: bold;">
        <?php echo $mensagem; ?>
    </p>
<?php endif; ?>

<!-- FORMULÁRIO -->
<h3><i class="fas fa-paper-plane"></i> Fale conosco</h3>

<form method="POST">

<label>Nome</label>
<input type="text" name="nome" required>

<label>Email</label>
<input type="email" name="email" required>

<label>Mensagem</label>
<textarea name="mensagem" required></textarea>

<br>
<button type="submit" class="btn btn-primary">
    <i class="fas fa-paper-plane"></i> Enviar
</button>

</form>

<!-- MAPA -->
<div class="map-container">
<iframe 
src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3838.513807983534!2d-48.11235582347999!3d-15.829570084815776!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x935bccfba936f021%3A0x6f07a0a68fc87b4a!2sCEP%20-%20ET%20de%20Ceil%C3%A2ndia!5e0!3m2!1spt-BR!2sbr!4v1763260149736!5m2!1spt-BR!2sbr"
width="100%" 
height="450" 
style="border:0;" 
allowfullscreen="" 
loading="lazy">
</iframe>
</div>

</main>

<footer>
<p>&copy; 2026 ConectaSolidária</p>
</footer>

</body>
</html>
