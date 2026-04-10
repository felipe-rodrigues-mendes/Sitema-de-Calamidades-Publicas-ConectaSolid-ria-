<?php

require_once __DIR__ . '/../models/dao/DonationDAO.php';
require_once __DIR__ . '/../models/dao/CampaignDAO.php';
require_once __DIR__ . '/../models/dao/ItemDAO.php';
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/SessionManager.php';

/**
 * Controller para gerenciar operações de doação.
 * Compatível com o schema atual: doacao + item_doacao.
 */
class DonationController {
    private $donationDAO;
    private $campaignDAO;
    private $itemDAO;

    public function __construct() {
        $this->donationDAO = new DonationDAO();
        $this->campaignDAO = new CampaignDAO();
        $this->itemDAO = new ItemDAO();
    }

    /**
     * Renderiza formulário de criação de doação
     */
    public function createForm(): void {
        SessionManager::requireLogin();

        $mensagem = "";
        $tipoMensagem = "";
        $campaigns = $this->campaignDAO->findAllActive();
        $selectedCampaignId = isset($_GET['campanha_id']) ? (int)$_GET['campanha_id'] : 0;
        $campaignTitle = isset($_GET['campanha']) ? trim((string)$_GET['campanha']) : '';
        $itensSelecionadosOld = [];
        $quantidadesOld = [];
        $campaignItemsMap = [];

        if ($selectedCampaignId <= 0 && $campaignTitle !== '') {
            $campaignByTitle = $this->campaignDAO->findByTitle($campaignTitle);
            if ($campaignByTitle !== null) {
                $selectedCampaignId = (int)$campaignByTitle->id;
            }
        }

        foreach ($campaigns as $campaign) {
            $campaignItemsMap[(int)$campaign->id] = $this->itemDAO->findByCampaign((int)$campaign->id);
        }

        include __DIR__ . '/../views/donations/create.php';
    }

    /**
     * Processa submissão de doação
     */
    public function store(): void {
        SessionManager::requireLogin();

        $usuario_id = SessionManager::getUserId();
        $mensagem = "";
        $tipoMensagem = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $campanha_id = isset($_POST['campanha_id']) ? (int)$_POST['campanha_id'] : 0;
            $descricao = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';
            $itensSelecionados = isset($_POST['itens']) ? (array)$_POST['itens'] : [];
            $quantidades = isset($_POST['quantidades']) ? (array)$_POST['quantidades'] : [];
            $selectedCampaignId = $campanha_id;
            $itensSelecionadosOld = $itensSelecionados;
            $quantidadesOld = $quantidades;

            if ($campanha_id <= 0) {
                $mensagem = 'Selecione uma campanha válida.';
                $tipoMensagem = 'erro';
            } elseif (count($itensSelecionados) === 0) {
                $mensagem = 'Selecione pelo menos 1 item para doação.';
                $tipoMensagem = 'erro';
            } else {
                $categoriasPermitidas = $this->itemDAO->findByCampaign($campanha_id);
                $categoriasPermitidasIds = array_map(static function (array $item): int {
                    return (int)$item['id'];
                }, $categoriasPermitidas);
                $categoriasPermitidasLookup = array_flip($categoriasPermitidasIds);

                if (empty($categoriasPermitidasLookup)) {
                    $mensagem = 'Esta campanha não possui necessidades cadastradas no momento.';
                    $tipoMensagem = 'erro';
                }

                $itens = [];
                if (empty($mensagem)) {
                    foreach ($itensSelecionados as $categoria_id) {
                        $categoria_id = (int)$categoria_id;
                        $quantidade = isset($quantidades[$categoria_id]) ? (int)$quantidades[$categoria_id] : 0;

                        if ($categoria_id <= 0 || $quantidade <= 0) {
                            $mensagem = 'Informe uma quantidade válida para cada item.';
                            $tipoMensagem = 'erro';
                            break;
                        }

                        if (!isset($categoriasPermitidasLookup[$categoria_id])) {
                            $mensagem = 'Um dos itens selecionados não pertence às necessidades da campanha.';
                            $tipoMensagem = 'erro';
                            break;
                        }

                        $itens[$categoria_id] = $quantidade;
                    }
                }

                if (empty($mensagem)) {
                    try {
                        $this->donationDAO->create($usuario_id, $campanha_id, $descricao, $itens);
                        header('Location: index.php?page=dashboard&sucesso=1');
                        exit;
                    } catch (Exception $e) {
                        $mensagem = 'Erro ao registrar doação: ' . $e->getMessage();
                        $tipoMensagem = 'erro';
                    }
                }
            }
        }

        $campaigns = $this->campaignDAO->findAllActive();
        $campaignItemsMap = [];
        foreach ($campaigns as $campaign) {
            $campaignItemsMap[(int)$campaign->id] = $this->itemDAO->findByCampaign((int)$campaign->id);
        }

        include __DIR__ . '/../views/donations/create.php';
    }

    /**
     * Renderiza dashboard com doações do usuário
     */
    public function dashboard(): void {
        SessionManager::requireLogin();

        $usuario_id = SessionManager::getUserId();
        $doacoes = $this->donationDAO->findByUserId($usuario_id);

        foreach ($doacoes as &$doacao) {
            $campanha = $this->campaignDAO->findById((int)$doacao->campanha_id);
            $doacao->campanha_nome = $campanha ? $campanha->titulo : 'N/A';
            $doacao->itens = $this->donationDAO->getItems((int)$doacao->id);
        }
        unset($doacao);

        include __DIR__ . '/../views/user/dashboard.php';
    }
}
