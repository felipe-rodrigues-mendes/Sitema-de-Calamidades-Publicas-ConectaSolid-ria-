<?php
// View: Homepage
// Renderizada por PublicController::home()
SessionManager::start();

$primeiraCampanha = $campanhas[0] ?? null;
$doarUrl = '';
if ($primeiraCampanha) {
    if (SessionManager::isAuthenticated()) {
        $doarUrl = 'index.php?page=donation_create&campanha_id=' . (int)$primeiraCampanha->id;
    } else {
        $doarUrl = 'index.php?page=register&redirect=' . urlencode('index.php?page=donation_create&campanha_id=' . (int)$primeiraCampanha->id);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ConectaSolidária - Home</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php include(__DIR__ . '/../layouts/navbar.php'); ?>

<main>
    <section class="banner home-hero">
        <div class="hero-copy">
            <span class="eyebrow">Juntos em cada emergência</span>
            <h1>Quando a emergência aumenta, a solidariedade precisa agir rápido</h1>
            <p>Encontre campanhas ativas, leve doações aos pontos oficiais e acompanhe o impacto da sua ajuda do início ao fim.</p>

            <div class="hero-actions">
                <?php if ($primeiraCampanha): ?>
                    <a href="<?php echo htmlspecialchars($doarUrl); ?>" class="btn-primary">
                        <i class="fas fa-hand-holding-heart"></i> Doe agora
                    </a>
                <?php endif; ?>
                <a href="#como-ajuda" class="btn-primary btn-secondary-style">
                    <i class="fas fa-route"></i> Como doar
                </a>
            </div>

            <div class="hero-metrics">
                <div class="metric-card">
                    <strong><?php echo count($campanhas); ?></strong>
                    <span>Campanhas ativas</span>
                </div>
                <div class="metric-card">
                    <strong><?php echo $pontosCount; ?></strong>
                    <span>Pontos de coleta</span>
                </div>
                <div class="metric-card">
                    <strong>100%</strong>
                    <span>Transparência nas doações</span>
                </div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-panel">
                <h3>Campanhas em destaque</h3>
                <p>As ações abaixo representam necessidades urgentes para as regiões mais afetadas.</p>
                <ul class="hero-list">
                    <?php foreach (array_slice($campanhas, 0, 3) as $campanha): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($campanha->titulo); ?></strong>
                            — <?php echo count($campanha->necessidades); ?> necessidades principais
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </section>

    <section class="section-box donate-process" id="como-ajuda">
        <div class="donate-header">
            <div>
                <span class="eyebrow">Comece agora</span>
                <h2>Como doar em 3 passos</h2>
            </div>
        </div>

        <p class="section-intro">Ajudar é fácil: escolha a campanha com necessidade real, separe os itens solicitados e entregue sua contribuição no ponto oficial mais próximo.</p>

        <div class="step-grid">
            <article class="step-card">
                <div class="step-badge">1</div>
                <h3>Escolha a campanha</h3>
                <p>Selecione uma campanha ativa e veja exatamente quais itens fazem mais diferença.</p>
            </article>
            <article class="step-card">
                <div class="step-badge">2</div>
                <h3>Prepare a doação</h3>
                <p>Separe os itens necessários e ajuste as quantidades conforme a solicitação da campanha.</p>
            </article>
            <article class="step-card">
                <div class="step-badge">3</div>
                <h3>Entregue no ponto</h3>
                <p>Leve sua doação a um ponto de coleta oficial e acompanhe a confirmação do recebimento.</p>
            </article>
        </div>

        <div class="help-footer">
            <p class="help-note"><strong>Dica:</strong> doações organizadas chegam mais rápido a quem precisa.</p>
        </div>
    </section>

    <section class="campaigns-section">
        <h2 class="titulo-cidades">Campanhas Ativas</h2>

        <div class="carrossel-topo">
            <button class="seta-carrossel" id="btnEsquerdaCidades" type="button" aria-label="Voltar campanhas">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="seta-carrossel" id="btnDireitaCidades" type="button" aria-label="Avançar campanhas">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <div class="card-container-horizontal" id="carrosselCidades">
            <?php foreach ($campanhas as $campanha): ?>
                <div class="card">
                    <img src="<?php echo htmlspecialchars($campanha->imagem); ?>" alt="<?php echo htmlspecialchars($campanha->titulo); ?>">
                    <h3><?php echo htmlspecialchars($campanha->titulo); ?></h3>

                    <?php if (!empty($campanha->necessidades)): ?>
                        <ul>
                            <?php foreach ($campanha->necessidades as $necessidade): ?>
                                <li>
                                    <?php echo htmlspecialchars($necessidade['categoria_nome']); ?>
                                    <br>
                                    <span class="qtd-necessaria">
                                        Necessário: <?php echo (int)$necessidade['quantidade_necessaria']; ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="sem-necessidades">Nenhuma necessidade cadastrada.</p>
                    <?php endif; ?>

                    <?php if (SessionManager::isAuthenticated()): ?>
                        <a href="index.php?page=donation_create&campanha_id=<?php echo (int)$campanha->id; ?>" class="btn">
                            <i class="fas fa-hand-holding-heart"></i> Doar
                        </a>
                    <?php else: ?>
                        <a href="index.php?page=register&redirect=<?php echo urlencode('index.php?page=donation_create&campanha_id=' . (int)$campanha->id); ?>" class="btn">
                            <i class="fas fa-hand-holding-heart"></i> Doar
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<footer>
    <p>© 2026 ConectaSolidária</p>
</footer>

<script>
    const carrossel = document.getElementById('carrosselCidades');
    const btnEsquerda = document.getElementById('btnEsquerdaCidades');
    const btnDireita = document.getElementById('btnDireitaCidades');

    if (carrossel && btnEsquerda && btnDireita) {
        function atualizarBotoes() {
            btnEsquerda.disabled = carrossel.scrollLeft <= 0;
            btnDireita.disabled = carrossel.scrollLeft + carrossel.clientWidth >= carrossel.scrollWidth - 5;
        }

        function mover(direcao) {
            const card = carrossel.querySelector('.card');
            const distancia = card ? card.offsetWidth + 20 : 340;
            carrossel.scrollBy({ left: direcao * distancia, behavior: 'smooth' });
        }

        btnEsquerda.addEventListener('click', () => mover(-1));
        btnDireita.addEventListener('click', () => mover(1));
        carrossel.addEventListener('scroll', atualizarBotoes);
        window.addEventListener('load', atualizarBotoes);
        window.addEventListener('resize', atualizarBotoes);
    }
</script>

</body>
</html>
