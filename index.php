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
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    .titulo-cidades {
        text-align: center;
        margin: 30px 0 15px;
    }

    .carrossel-topo {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        max-width: 1200px;
        margin: 0 auto 15px;
        padding: 0 10px;
    }

    .seta-carrossel {
        width: 42px;
        height: 42px;
        border: none;
        border-radius: 50%;
        background-color: #2563eb;
        color: white;
        cursor: pointer;
        font-size: 18px;
        transition: 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .seta-carrossel:hover {
        background-color: #1d4ed8;
        transform: scale(1.05);
    }

    .seta-carrossel:disabled {
        background-color: #94a3b8;
        cursor: not-allowed;
        transform: none;
    }

    .card-container-horizontal {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding: 10px 10px 15px;
        max-width: 1200px;
        margin: 0 auto 30px;
        scrollbar-width: none;
    }

    .card-container-horizontal::-webkit-scrollbar {
        display: none;
    }

    .card {
        min-width: 320px;
        max-width: 320px;
        flex: 0 0 320px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: 0.3s;
        border: 1px solid #e5e7eb;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .card img {
        width: 100%;
        height: 190px;
        object-fit: cover;
        display: block;
    }

    .card h3 {
        font-size: 22px;
        margin: 18px 18px 12px;
        color: #111827;
    }

    .card ul {
        margin: 0 18px 18px;
        padding-left: 18px;
        color: #374151;
    }

    .card ul li {
        margin-bottom: 6px;
    }

    .card .btn {
        display: inline-block;
        margin: 0 18px 18px;
        padding: 10px 16px;
        background-color: #2563eb;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: bold;
        transition: 0.3s;
    }

    .card .btn:hover {
        background-color: #1d4ed8;
    }

    @media (max-width: 768px) {
        .carrossel-topo {
            justify-content: center;
        }

        .card {
            min-width: 280px;
            max-width: 280px;
            flex: 0 0 280px;
        }

        .card img {
            height: 170px;
        }

        .card h3 {
            font-size: 20px;
        }
    }
</style>
</head>

<body>

<?php include("menu.php"); ?>

<main>

<section class="banner">
    <h2>Juntos podemos ajudar mais pessoas</h2>
    <p>Conectamos doadores com comunidades afetadas por calamidades.</p>
</section>

<h2 class="titulo-cidades">Cidades que precisam da sua ajuda</h2>

<div class="carrossel-topo">
    <button class="seta-carrossel" id="btnEsquerdaCidades" type="button" aria-label="Voltar cidades">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button class="seta-carrossel" id="btnDireitaCidades" type="button" aria-label="Avançar cidades">
        <i class="fas fa-chevron-right"></i>
    </button>
</div>

<div class="card-container-horizontal" id="carrosselCidades">

<?php foreach ($cidades as $cidade): ?>

<div class="card">
    <img src="<?php echo htmlspecialchars($cidade['imagem']); ?>" alt="<?php echo htmlspecialchars($cidade['nome']); ?>">
    <h3><?php echo htmlspecialchars($cidade['nome']); ?></h3>

    <ul>
    <?php foreach ($cidade['necessidades'] as $item): ?>
        <li><?php echo htmlspecialchars($item); ?></li>
    <?php endforeach; ?>
    </ul>

    <?php if (isset($_SESSION["usuario_id"])): ?>
        <a href="doacao.php?cidade=<?php echo urlencode($cidade['nome']); ?>" class="btn">
            <i class="fas fa-hand-holding-heart"></i> Doar
        </a>
    <?php else: ?>
        <a href="cadastro.php?cidade=<?php echo urlencode($cidade['nome']); ?>" class="btn">
            <i class="fas fa-hand-holding-heart"></i> Doar
        </a>
    <?php endif; ?>
</div>

<?php endforeach; ?>

</div>

</main>

<footer>
    <p>© 2026 ConectaSolidária</p>
</footer>

<script>
    const carrosselCidades = document.getElementById('carrosselCidades');
    const btnEsquerdaCidades = document.getElementById('btnEsquerdaCidades');
    const btnDireitaCidades = document.getElementById('btnDireitaCidades');

    if (carrosselCidades && btnEsquerdaCidades && btnDireitaCidades) {
        function atualizarBotoesCidades() {
            btnEsquerdaCidades.disabled = carrosselCidades.scrollLeft <= 0;
            btnDireitaCidades.disabled = carrosselCidades.scrollLeft + carrosselCidades.clientWidth >= carrosselCidades.scrollWidth - 5;
        }

        function moverCidades(direcao) {
            const card = carrosselCidades.querySelector('.card');
            const distancia = card ? card.offsetWidth + 20 : 340;

            carrosselCidades.scrollBy({
                left: direcao * distancia,
                behavior: 'smooth'
            });
        }

        btnEsquerdaCidades.addEventListener('click', function() {
            moverCidades(-1);
        });

        btnDireitaCidades.addEventListener('click', function() {
            moverCidades(1);
        });

        carrosselCidades.addEventListener('scroll', atualizarBotoesCidades);
        window.addEventListener('load', atualizarBotoesCidades);
        window.addEventListener('resize', atualizarBotoesCidades);
    }
</script>

</body>
</html>
