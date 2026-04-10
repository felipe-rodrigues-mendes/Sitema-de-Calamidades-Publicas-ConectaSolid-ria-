<?php

require_once __DIR__ . '/../models/dao/CampaignDAO.php';
require_once __DIR__ . '/../models/dao/PointOfCollectionDAO.php';
require_once __DIR__ . '/SessionManager.php';

/**
 * Controller para gerenciar páginas públicas.
 * Compatível com o schema atual: campanha, necessidade, ponto_coleta, endereco.
 */
class PublicController {
    private $campaignDAO;
    private $pointDAO;

    public function __construct() {
        $this->campaignDAO = new CampaignDAO();
        $this->pointDAO = new PointOfCollectionDAO();
    }

    /**
     * Renderiza homepage com carousel de campanhas
     */
    public function home(): void {
        $campanhas = $this->campaignDAO->findAllActive();

        $imagensCampanhas = [
            'Rio Grande do Sul' => 'assets/uploas/enchente Rio Grande do Sul.jpg',
            'Bahia' => 'assets/uploas/enchente Bahia.jpg',
            'Minas Gerais' => 'assets/uploas/Minas gerais.jpg',
            'São Paulo' => 'assets/uploas/enchente sao paulo.jpg',
            'Santa Catarina' => 'assets/uploas/santa catarina.jpg',
            'Paraná' => 'assets/uploas/Parána.jpg'
        ];

        foreach ($campanhas as &$campanha) {
            $campanha->imagem = isset($imagensCampanhas[$campanha->titulo])
                ? $imagensCampanhas[$campanha->titulo]
                : 'assets/uploas/logo.PNG';
            $campanha->necessidades = $this->campaignDAO->getNecessidades((int)$campanha->id);
        }
        unset($campanha);

        include __DIR__ . '/../views/public/home.php';
    }

    /**
     * Renderiza página sobre
     */
    public function about(): void {
        include __DIR__ . '/../views/public/about.php';
    }

    /**
     * Renderiza página de pontos de coleta
     */
    public function collectionPoints(): void {
        $cidadeSelecionada = isset($_GET['cidade']) ? trim($_GET['cidade']) : '';
        $pontos = [];

        if (!empty($cidadeSelecionada)) {
            $pontos = $this->pointDAO->findByCity($cidadeSelecionada);
        } else {
            $pontos = $this->pointDAO->findAll();
        }

        include __DIR__ . '/../views/public/collection_points.php';
    }

    /**
     * Renderiza formulário de contato
     */
    public function contact(): void {
        $mensagem = '';
        $tipoMensagem = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $assunto = trim($_POST['assunto'] ?? '');
            $conteudo = trim($_POST['conteudo'] ?? '');

            if (empty($nome) || empty($email) || empty($assunto) || empty($conteudo)) {
                $mensagem = 'Todos os campos são obrigatórios.';
                $tipoMensagem = 'erro';
            } else {
                $mensagem = 'Mensagem enviada com sucesso! Entraremos em contato em breve.';
                $tipoMensagem = 'sucesso';
            }
        }

        include __DIR__ . '/../views/public/contact.php';
    }
}

