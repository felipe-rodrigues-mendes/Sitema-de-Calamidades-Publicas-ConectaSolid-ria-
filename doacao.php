<?php
include("conexao.php");
include("proteger.php");

$mensagem = "";

// Buscar campanhas ativas
$sqlCampanhas = "SELECT id, titulo FROM campanhas WHERE status = 'ativa'";
$resultadoCampanhas = $conn->query($sqlCampanhas);

if (!$resultadoCampanhas) {
    die("Erro ao buscar campanhas: " . $conn->error);
}

// Lista fixa de itens para doação
$itensDisponiveis = [
    "Água potável",
    "Alimentos não perecíveis",
    "Kits de higiene",
    "Roupas e agasalhos",
    "Colchões e cobertores",
    "Fraldas descartáveis",
    "Materiais de limpeza",
    "Remédios",
    "Material de construção",
    "Ferramentas"
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_SESSION["usuario_id"];
    $campanha_id = intval($_POST["campanha_id"]);
    $descricao = trim($_POST["descricao"]);

    $itensSelecionados = isset($_POST["itens"]) ? $_POST["itens"] : [];
    $quantidades = isset($_POST["quantidades"]) ? $_POST["quantidades"] : [];

    if (count($itensSelecionados) == 0) {
        $mensagem = "Selecione pelo menos 1 item para doação.";
    } else {
        $sql = "INSERT INTO doacoes (usuario_id, campanha_id, item, quantidade, descricao) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Erro no prepare: " . $conn->error);
        }

        $sucesso = true;

        foreach ($itensSelecionados as $item) {
            $quantidade = isset($quantidades[$item]) ? intval($quantidades[$item]) : 0;

            if ($quantidade <= 0) {
                $sucesso = false;
                $mensagem = "Informe uma quantidade válida para cada item selecionado.";
                break;
            }

            $stmt->bind_param("iisis", $usuario_id, $campanha_id, $item, $quantidade, $descricao);

            if (!$stmt->execute()) {
                $sucesso = false;
                $mensagem = "Erro ao registrar doação: " . $stmt->error;
                break;
            }
        }

        if ($sucesso) {
            $mensagem = "Doação registrada com sucesso!";
        }

        $stmt->close();
    }
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

        .item-doacao input[type="number"]:disabled {
            background-color: #e5e7eb;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .mensagem {
            margin-bottom: 15px;
            font-weight: bold;
            color: green;
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
        <p class="aviso">Você pode selecionar quantos itens quiser para doar.</p>

        <?php if (!empty($mensagem)) : ?>
            <p class="mensagem"><?php echo htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>

        <form method="POST" id="formDoacao">
            <label for="campanha_id">Campanha</label>
            <select name="campanha_id" id="campanha_id" required>
                <option value="">Selecione uma campanha</option>
                <?php while ($campanha = $resultadoCampanhas->fetch_assoc()) : ?>
                    <option value="<?php echo $campanha["id"]; ?>">
                        <?php echo htmlspecialchars($campanha["titulo"]); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <div class="itens-box">
                <h3>Itens para Doação</h3>

                <?php foreach ($itensDisponiveis as $item) : ?>
                    <?php $itemId = md5($item); ?>
                    <div class="item-doacao">
                        <label>
                            <input 
                                type="checkbox" 
                                name="itens[]" 
                                value="<?php echo htmlspecialchars($item); ?>" 
                                class="checkbox-item"
                                data-target="qtd_<?php echo $itemId; ?>"
                            >
                            <?php echo htmlspecialchars($item); ?>
                        </label>

                        <label for="qtd_<?php echo $itemId; ?>">Quantidade</label>
                        <input 
                            type="number" 
                            name="quantidades[<?php echo htmlspecialchars($item); ?>]" 
                            id="qtd_<?php echo $itemId; ?>" 
                            min="1"
                            placeholder="Ex: 5"
                            disabled
                        >
                    </div>
                <?php endforeach; ?>
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
    const checkboxes = document.querySelectorAll('.checkbox-item');

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
</script>

</body>
</html>
