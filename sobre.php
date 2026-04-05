<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Sobre - ConectaSolidária</title>

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
.sobre-container {
    max-width: 1000px;
    margin: auto;
    padding: 40px;
}

.sobre-container h2 {
    text-align: center;
    margin-bottom: 30px;
}

.card-sobre {
    background: white;
    padding: 25px;
    margin-bottom: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: 0.3s;
}

.card-sobre:hover {
    transform: scale(1.02);
}

.card-sobre i {
    font-size: 30px;
    color: #0d6efd;
    margin-bottom: 10px;
}

.card-sobre h3 {
    margin-bottom: 10px;
}

.card-sobre ul {
    padding-left: 20px;
}

.banner-sobre {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
    color: white;
    text-align: center;
    padding: 60px 20px;
}

.banner-sobre h1 {
    font-size: 36px;
}

.banner-sobre p {
    font-size: 18px;
}
</style>

</head>

<body>

<?php include("menu.php"); ?>

<section class="banner-sobre">
    <h1>Sobre o ConectaSolidária</h1>
    <p>Conectando pessoas que querem ajudar com quem mais precisa</p>
</section>

<main class="sobre-container">

    <div class="card-sobre">
        <i class="fas fa-users"></i>
        <h3>Quem somos</h3>
        <p>
            O ConectaSolidária é uma plataforma desenvolvida para conectar doadores a comunidades afetadas por calamidades públicas, promovendo solidariedade através da tecnologia.
        </p>
    </div>

    <div class="card-sobre">
        <i class="fas fa-bullseye"></i>
        <h3>Nosso propósito</h3>
        <p>
            Facilitar a arrecadação e distribuição de doações de forma rápida, organizada e eficiente, ajudando pessoas em momentos críticos.
        </p>
    </div>

    <div class="card-sobre">
        <i class="fas fa-laptop"></i>
        <h3>O que o sistema faz</h3>
        <ul>
            <li>Cadastro de usuário</li>
            <li>Divulgação de necessidades</li>
            <li>Conexão entre doadores e comunidades</li>
            <li>Centralização de informações</li>
        </ul>
    </div>

    <div class="card-sobre">
        <i class="fas fa-heart"></i>
        <h3>Nossa missão</h3>
        <p>
            Utilizar a tecnologia como ferramenta de impacto social, ajudando a salvar vidas e apoiar comunidades em situações de emergência.
        </p>
    </div>

</main>

<footer>
    <p>&copy; 2026 ConectaSolidária</p>
</footer>

</body>
</html>
