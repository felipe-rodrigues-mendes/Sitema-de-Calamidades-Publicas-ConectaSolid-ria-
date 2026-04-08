<?php
include("conexao.php");
include("proteger.php");

$nome_usuario = $_SESSION["usuario_nome"];
$usuario_id = $_SESSION["usuario_id"];

// Buscar doações do usuário com nome da campanha
$sqlDoacoes = "
    SELECT 
        d.id,
        d.data_doacao,
        d.status,
        d.descricao,
        c.titulo AS campanha
    FROM doacoes d
    INNER JOIN campanhas c ON d.campanha_id = c.id
    WHERE d.usuario_id = ?
    ORDER BY d.data_doacao DESC
";

$stmt = $conn->prepare($sqlDoacoes);

if (!$stmt) {
    die("Erro ao preparar consulta: " . $conn->error);
}

$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultadoDoacoes = $stmt->get_result();
$totalDoacoes = $resultadoDoacoes->num_rows;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Usuário</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .painel-section {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .painel-topo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .painel-topo-texto h2 {
            margin: 0 0 8px 0;
        }

        .painel-topo-texto p {
            margin: 4px 0;
            color: #444;
        }

        .btn-doar {
            display: inline-block;
            padding: 12px 18px;
            background-color: #2563eb;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-doar:hover {
            background-color: #1d4ed8;
        }

        .total-doacoes {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e3a8a;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-weight: bold;
        }

        .carrossel-topo {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 15px;
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

        .cards-horizontal {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding-bottom: 10px;
            scroll-behavior: smooth;
            scrollbar-width: none;
        }

        .cards-horizontal::-webkit-scrollbar {
            display: none;
        }

        .card-doacao {
            min-width: 340px;
            max-width: 340px;
            flex: 0 0 340px;
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: 0.3s;
            border-left: 6px solid #2563eb;
            box-sizing: border-box;
        }

        .card-doacao:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
        }

        .card-doacao h3 {
            margin-top: 0;
            margin-bottom: 12px;
            color: #111827;
            font-size: 20px;
        }

        .info-doacao {
            margin-bottom: 12px;
            color: #374151;
        }

        .info-doacao p {
            margin: 8px 0;
        }

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: bold;
            text-transform: capitalize;
        }

        .status.pendente {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status.recebida {
            background-color: #dcfce7;
            color: #166534;
        }

        .descricao {
            margin-top: 10px;
            margin-bottom: 15px;
            color: #444;
            background: #f8fafc;
            padding: 10px;
            border-radius: 8px;
        }

        .lista-itens {
            margin-top: 15px;
            padding-left: 0;
            list-style: none;
        }

        .lista-itens li {
            background: #f1f5f9;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 8px;
            color: #374151;
        }

        .sem-doacoes {
            background: #f9fafb;
            border: 1px dashed #ccc;
            padding: 20px;
            border-radius: 10px;
            color: #555;
        }

        @media (max-width: 768px) {
            .painel-topo {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-doar {
                width: 100%;
                text-align: center;
            }

            .card-doacao {
                min-width: 280px;
                max-width: 280px;
                flex: 0 0 280px;
            }

            .carrossel-topo {
                justify-content: center;
            }
        }
    </style>
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
    <section class="painel-section">
        <div class="painel-topo">
            <div class="painel-topo-texto">
                <h2>Minhas Doações</h2>
                <p>Bem-vindo, <strong><?php echo htmlspecialchars($nome_usuario); ?></strong> 👋</p>
            </div>

            <a href="doacao.php" class="btn-doar">Nova Doação</a>
        </div>

        <div class="total-doacoes">
            Total de doações realizadas: <strong><?php echo $totalDoacoes; ?></strong>
        </div>

        <?php if ($resultadoDoacoes->num_rows > 0) : ?>
            <div class="carrossel-topo">
                <button class="seta-carrossel" id="btnEsquerda" type="button" aria-label="Voltar">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="seta-carrossel" id="btnDireita" type="button" aria-label="Avançar">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <div class="cards-horizontal" id="carrosselDoacoes">
                <?php while ($doacao = $resultadoDoacoes->fetch_assoc()) : ?>
                    <div class="card-doacao">
                        <h3>
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($doacao["campanha"]); ?>
                        </h3>

                        <div class="info-doacao">
                            <p><strong>📅 Data:</strong> <?php echo date("d/m/Y H:i", strtotime($doacao["data_doacao"])); ?></p>
                            <p>
                                <strong>📦 Status:</strong>
                                <span class="status <?php echo htmlspecialchars($doacao["status"]); ?>">
                                    <?php echo htmlspecialchars($doacao["status"]); ?>
                                </span>
                            </p>
                        </div>

                        <?php if (!empty($doacao["descricao"])) : ?>
                            <p class="descricao">
                                <strong>Descrição:</strong> <?php echo htmlspecialchars($doacao["descricao"]); ?>
                            </p>
                        <?php endif; ?>

                        <h4>Itens doados:</h4>
                        <ul class="lista-itens">
                            <?php
                            $sqlItens = "
                                SELECT 
                                    ci.nome AS item,
                                    id.quantidade
                                FROM itens_doacao id
                                INNER JOIN categorias_itens ci ON id.categoria_id = ci.id
                                WHERE id.doacao_id = ?
                            ";

                            $stmtItens = $conn->prepare($sqlItens);

                            if ($stmtItens) {
                                $stmtItens->bind_param("i", $doacao["id"]);
                                $stmtItens->execute();
                                $resultadoItens = $stmtItens->get_result();

                                while ($item = $resultadoItens->fetch_assoc()) {
                                    echo "<li>" . htmlspecialchars($item["item"]) . " - Quantidade: " . htmlspecialchars($item["quantidade"]) . "</li>";
                                }

                                $stmtItens->close();
                            }
                            ?>
                        </ul>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <div class="sem-doacoes">
                Você ainda não realizou nenhuma doação.
            </div>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>© 2026 ConectaSolidária</p>
</footer>

<script>
    const carrossel = document.getElementById('carrosselDoacoes');
    const btnEsquerda = document.getElementById('btnEsquerda');
    const btnDireita = document.getElementById('btnDireita');

    if (carrossel && btnEsquerda && btnDireita) {
        function atualizarBotoes() {
            btnEsquerda.disabled = carrossel.scrollLeft <= 0;
            btnDireita.disabled = carrossel.scrollLeft + carrossel.clientWidth >= carrossel.scrollWidth - 5;
        }

        function moverCarrossel(direcao) {
            const card = carrossel.querySelector('.card-doacao');
            const distancia = card ? card.offsetWidth + 20 : 360;
            carrossel.scrollBy({
                left: direcao * distancia,
                behavior: 'smooth'
            });
        }

        btnEsquerda.addEventListener('click', function () {
            moverCarrossel(-1);
        });

        btnDireita.addEventListener('click', function () {
            moverCarrossel(1);
        });

        carrossel.addEventListener('scroll', atualizarBotoes);
        window.addEventListener('load', atualizarBotoes);
        window.addEventListener('resize', atualizarBotoes);
    }
</script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
