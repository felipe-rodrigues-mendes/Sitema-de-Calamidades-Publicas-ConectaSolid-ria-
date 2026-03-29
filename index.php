<?php
$cidades = [
    [
        "nome" => "Rio Grande do Sul",
        "imagem" => "images/enchente Rio Grande do Sul.jpg",
        "necessidades" => [
            "Remédios",
            "Roupas de cama e banho",
            "Kits de higiene",
            "Fraldas descartáveis",
            "Roupas íntimas novas",
            "Calçados fechados",
            "Material de construção",
            "Ferramentas"
        ]
    ],
    [
        "nome" => "Bahia",
        "imagem" => "images/enchente Bahia.jpg",
        "necessidades" => [
            "Água potável",
            "Alimentos não perecíveis",
            "Colchões e cobertores",
            "Roupas de cama e banho",
            "Kits de higiene",
            "Fraldas descartáveis",
            "Materiais de limpeza",
            "Roupas e agasalhos"
        ]
    ],
    [
        "nome" => "Minas Gerais",
        "imagem" => "images/Minas gerais.jpg",
        "necessidades" => [
            "Produtos de higiene",
            "Água potável",
            "Ração",
            "Caixas de transporte para animais",
            "Materiais de limpeza",
            "Roupas e agasalhos",
            "Colchões e cobertores",
            "Alimentos não perecíveis"
        ]
    ],
    [
        "nome" => "São Paulo",
        "imagem" => "images/enchente sao paulo.jpg",
        "necessidades" => [
            "Colchões e cobertores",
            "Roupas de cama e banho",
            "Kits de higiene",
            "Água potável",
            "Alimentos não perecíveis",
            "Materiais de limpeza",
            "Roupas e agasalhos",
            "Fraldas descartáveis"
        ]
    ],
    [
        "nome" => "Santa Catarina",
        "imagem" => "images/santa catarina.jpg",
        "necessidades" => [
            "Água potável",
            "Alimentos não perecíveis",
            "Colchões e cobertores",
            "Roupas de cama e banho",
            "Kits de higiene",
            "Fraldas descartáveis",
            "Materiais de limpeza",
            "Roupas e agasalhos"
        ]
    ],
    [
        "nome" => "Paraná",
        "imagem" => "images/Parána.jpg",
        "necessidades" => [
            "Água potável",
            "Alimentos não perecíveis",
            "Colchões e cobertores",
            "Remédios",
            "Roupas de cama e banho",
            "Kits de higiene",
            "Materiais de limpeza",
            "Roupas e agasalhos"
        ]
    ]
];
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
        <img src="images/logo.png" class="logo">
    </a>
</div>

<nav>
    <a href="index.php" class="active">Início</a>
    <a href="cadastro.php">Cadastro</a>
    <a href="contato.php">Contato</a>
    <a href="sobre.php">Sobre</a>
</nav>

</header>

<main>

<section class="banner">
<h2>Juntos podemos ajudar mais pessoas</h2>
<p>Conectamos doadores com comunidades afetadas por calamidades.</p>
</section>

<h2>Cidades que precisam da sua ajuda</h2>

<div class="card-container">

<?php foreach ($cidades as $cidade): ?>

<div class="card">
    <img src="<?php echo htmlspecialchars($cidade['imagem']); ?>">
    <h3><?php echo htmlspecialchars($cidade['nome']); ?></h3>

    <ul>
    <?php foreach ($cidade['necessidades'] as $item): ?>
        <li><?php echo htmlspecialchars($item); ?></li>
    <?php endforeach; ?>
    </ul>

   <a href="cadastro.php?cidade=<?php echo urlencode($cidade['nome']); ?>" class="btn btn-primary">
    Doar
</a>

</div>

<?php endforeach; ?>

</div>

</main>

<footer>
<p>© 2026 Conecta Solidária</p>
</footer>

</body>
</html>
