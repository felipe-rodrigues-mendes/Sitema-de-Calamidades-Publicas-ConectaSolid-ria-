<?php

require_once __DIR__ . '/../models/dao/DonationDAO.php';
require_once __DIR__ . '/../models/dao/InventoryDAO.php';
require_once __DIR__ . '/../models/dao/PointOfCollectionDAO.php';
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/SessionManager.php';

/**
 * Controller para gerenciar operações administrativas.
 * Compatível com o schema atual: doacao, estoque, item_estoque.
 */
class AdminController {
    private $donationDAO;
    private $inventoryDAO;
    private $pointDAO;

    public function __construct() {
        $this->donationDAO = new DonationDAO();
        $this->inventoryDAO = new InventoryDAO();
        $this->pointDAO = new PointOfCollectionDAO();
    }

    /**
     * Verifica permissão de admin
     */
    private function requireAdmin(): void {
        SessionManager::requireRole('admin');
    }

    /**
     * Renderiza painel de gerenciamento de doações
     */
    public function manageDonations(): void {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receber_doacao'])) {
            $this->receiveDonation();
            return;
        }

        $filtro = isset($_GET['status']) ? $_GET['status'] : 'todos';
        $busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

        $doacoes = $this->donationDAO->findAll($filtro, $busca);
        $flash = SessionManager::getMessage();
        $mensagem = $flash['mensagem'] ?? '';
        $tipoMensagem = $flash['tipo'] ?? '';

        include __DIR__ . '/../views/admin/donations.php';
    }

    /**
     * Recebe doação e adiciona ao inventário
     */
    public function receiveDonation(): void {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receber_doacao'])) {
            if (!SessionManager::validateCsrfToken($_POST['csrf_token'] ?? null)) {
                SessionManager::setMessage('Sua sessão expirou. Atualize a página e tente novamente.', 'erro');
                header('Location: index.php?page=admin_donations');
                exit;
            }

            $doacao_id = (int)($_POST['doacao_id'] ?? 0);
            $codigoDoacao = trim((string)($_POST['codigo_doacao'] ?? ''));

            if ($doacao_id <= 0 && $codigoDoacao !== '') {
                $doacao = $this->donationDAO->findByPublicCode($codigoDoacao);
                $doacao_id = $doacao ? (int)$doacao->id : 0;
            }

            if ($doacao_id <= 0) {
                SessionManager::setMessage('Doação inválida. Confira o código informado.', 'erro');
            } else {
                try {
                    Database::getInstance()->beginTransaction();

                    $doacao = $this->donationDAO->findById($doacao_id);
                    if (!$doacao) {
                        throw new Exception('Doação não encontrada.');
                    }

                    if ($doacao->status === 'recebida') {
                        throw new Exception('Esta doação já foi recebida.');
                    }

                    if ((int)$doacao->ponto_id <= 0) {
                        throw new Exception('Doação sem ponto de coleta definido.');
                    }

                    $estoque_id = $this->inventoryDAO->getOrCreateEstoque((int)$doacao->ponto_id);

                    if (!$this->donationDAO->updateStatus($doacao_id, 'RECEBIDA')) {
                        throw new Exception('Erro ao atualizar status da doação.');
                    }

                    $items = $this->donationDAO->getItems($doacao_id);
                    foreach ($items as $item) {
                        $this->inventoryDAO->addOrUpdateItem(
                            $estoque_id,
                            (int)$item['categoria_id'],
                            (int)$item['quantidade']
                        );
                    }

                    Database::getInstance()->commit();
                    SessionManager::setMessage('Doação recebida e adicionada ao estoque com sucesso!', 'sucesso');
                } catch (Exception $e) {
                    Database::getInstance()->rollback();
                    SessionManager::setMessage('Erro: ' . $e->getMessage(), 'erro');
                }
            }
        }

        header('Location: index.php?page=admin_donations');
        exit;
    }

    /**
     * Renderiza visualização de estoque
     */
    public function viewInventory(): void {
        $this->requireAdmin();

        $estoquesAgrupados = $this->inventoryDAO->getInventoryByLocation();
        include __DIR__ . '/../views/admin/inventory.php';
    }
}
