<?php
include("conexao.php");
include("proteger.php");

$mensagem = "";
$tipoMensagem = "";
$cidadeSelecionada = isset($_GET["cidade"]) ? trim($_GET["cidade"]) : "";

// Buscar campanhas ativas
$sqlCampanhas = "SELECT id, titulo FROM campanhas WHERE status = 'ativa' ORDER BY titulo ASC";
$resultadoCampanhas = $conn->query($sqlCampanhas);

if (!$resultadoCampanhas) {
    die("Erro ao buscar campanhas: " . $conn->error);
}

// Buscar categorias de itens no banco
$sqlCategorias = "SELECT id, nome FROM categorias_itens ORDER BY nome ASC";
$resultadoCategorias = $conn->query($sqlCategorias);

if (!$resultadoCategorias) {
    die("Erro ao buscar categorias: " . $conn->error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_SESSION["usuario_id"];
    $campanha_id = isset($_POST["campanha_id"]) ? intval($_POST["campanha_id"]) : 0;
    $descricao = isset($_POST["descricao"]) ? trim($_POST["descricao"]) : "";

    $itensSelecionados = isset($_POST["itens"]) ? $_POST["itens"] : [];
    $quantidades = isset($_POST["quantidades"]) ? $_POST["quantidades"] : [];

    if ($campanha_id <= 0) {
        $mensagem = "Selecione uma campanha válida.";
        $tipoMensagem = "erro";
    } elseif (count($itensSelecionados) == 0) {
        $mensagem = "Selecione pelo menos 1 item para doação.";
        $tipoMensagem = "erro";
    } else {
        $conn->begin_transaction();

        try {
            $sqlDoacao = "INSERT INTO doacoes (usuario_id, campanha_id, descricao) VALUES (?, ?, ?)";
            $stmtDoacao = $conn->prepare($sqlDoacao);

            if (!$stmtDoacao) {
                throw new Exception("Erro ao preparar doação: " . $conn->error);
            }

            $stmtDoacao->bind_param("iis", $usuario_id, $campanha_id, $descricao);

            if (!$stmtDoacao->execute()) {
                throw new Exception("Erro ao registrar doação: " . $stmtDoacao->error);
            }

            $doacao_id = $conn->insert_id;
            $stmtDoacao->close();

            $sqlItem = "INSERT INTO itens_doacao (doacao_id, categoria_id, quantidade) VALUES (?, ?, ?)";
            $stmtItem = $conn->prepare($sqlItem);

            if (!$stmtItem) {
                throw new Exception("Erro ao preparar itens da doação: " . $conn->error);
            }

            foreach ($itensSelecionados as $categoria_id) {
                $categoria_id = intval($categoria_id);
                $quantidade = isset($quantidades[$categoria_id]) ? intval($quantidades[$categoria_id]) : 0;

                if ($categoria_id <= 0 || $quantidade <= 0) {
                    throw new Exception("Informe uma quantidade válida para cada item selecionado.");
                }

                $stmtItem->bind_param("iii", $doacao_id, $categoria_id, $quantidade);

                if (!$stmtItem->execute()) {
                    throw new Exception("Erro ao registrar item da doação: " . $stmtItem->error);
                }
            }

            $stmtItem->close();

            $conn->commit();
            $mensagem = "Doação registrada com sucesso!";
            $tipoMensagem = "sucesso";
        } catch (Exception $e) {
            $conn->rollback();
            $mensagem = $e->getMessage();
            $tipoMensagem = "erro";
        }
    }

    $resultadoCampanhas = $conn->query($sqlCampanhas);
    $resultadoCategorias = $conn->query($sqlCategorias);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Doação</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .form-section {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .form-section form {
            width: 100%;
            max-width: 100%;
        }

        .itens-box {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            width: 100%;
        }

        .itens-box h3 {
            grid-column: 1 / -1;
            margin-bottom: 10px;
        }

        .item-doacao {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-sizing: border-box;
        }

        .item-doacao label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }

        .item-doacao input[type="checkbox"] {
            margin-right: 8px;
            transform: scale(1.1);
            width: auto;
        }

        .item-doacao input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .item-doacao input[type="number"]:disabled,
        .item-doacao input[type="checkbox"]:disabled {
            background-color: #e5e7eb;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .mensagem {
            margin-bottom: 15px;
            font-weight: bold;
            padding: 12px;
            border-radius: 8px;
        }

        .mensagem.sucesso {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .mensagem.erro {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .aviso {
            color: #333;
            font-size: 14px;
            margin-bottom: 15px;
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
    <section class="form-section">
        <h2>Registrar Doação</h2>
        <p class="aviso">Primeiro selecione uma campanha. Depois escolha os itens que deseja doar.</p>

        <?php if (!empty($mensagem)) : ?>
            <p class="mensagem <?php echo $tipoMensagem; ?>">
                <?php echo htmlspecialchars($mensagem); ?>
            </p>
        <?php endif; ?>

        <form method="POST" id="formDoacao">
            <label for="campanha_id">Campanha</label>
            <select name="campanha_id" id="campanha_id" required>
                <option value="">Selecione uma campanha</option>
                <?php while ($campanha = $resultadoCampanhas->fetch_assoc()) : ?>
                    <option 
                        value="<?php echo $campanha["id"]; ?>"
                        <?php echo ($cidadeSelecionada === $campanha["titulo"]) ? "selected" : ""; ?>
                    >
                        <?php echo htmlspecialchars($campanha["titulo"]); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <div class="itens-box">
                <h3>Itens para Doação</h3>

                <?php while ($categoria = $resultadoCategorias->fetch_assoc()) : ?>
                    <div class="item-doacao">
                        <label>
                            <input 
                                type="checkbox" 
                                name="itens[]" 
                                value="<?php echo $categoria["id"]; ?>"
                                class="checkbox-item"
                                data-target="qtd_<?php echo $categoria["id"]; ?>"
                            >
                            <?php echo htmlspecialchars($categoria["nome"]); ?>
                        </label>

                        <label for="qtd_<?php echo $categoria["id"]; ?>">Quantidade</label>
                        <input 
                            type="number" 
                            name="quantidades[<?php echo $categoria["id"]; ?>]" 
                            id="qtd_<?php echo $categoria["id"]; ?>" 
                            min="1"
                            placeholder="Ex: 5"
                            disabled
                        >
                    </div>
                <?php endwhile; ?>
            </div>

            <label for="descricao">Descrição complementar</label>
            <textarea name="descricao" id="descricao" rows="4" placeholder="Ex: itens em bom estado, embalados, etc."></textarea>

            <button type="submit">Registrar Doação</button>
        </form>
    </section>
</main>

<footer>
    <p>© 2026 ConectaSolidária</p>
</footer>

<script>
    const selectCampanha = document.getElementById('campanha_id');
    const checkboxes = document.querySelectorAll('.checkbox-item');

    function atualizarEstadoItens() {
        const campanhaSelecionada = selectCampanha.value !== "";

        checkboxes.forEach(function(checkbox) {
            const targetId = checkbox.getAttribute('data-target');
            const campoQuantidade = document.getElementById(targetId);

            checkbox.disabled = !campanhaSelecionada;

            if (!campanhaSelecionada) {
                checkbox.checked = false;
                campoQuantidade.value = "";
                campoQuantidade.disabled = true;
            }
        });
    }

    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const targetId = this.getAttribute('data-target');
            const campoQuantidade = document.getElementById(targetId);

            if (this.checked) {
                campoQuantidade.disabled = false;
                campoQuantidade.focus();
            } else {
                campoQuantidade.value = "";
                campoQuantidade.disabled = true;
            }
        });
    });

    selectCampanha.addEventListener('change', atualizarEstadoItens);

    atualizarEstadoItens();
</script>

</body>
</html>
