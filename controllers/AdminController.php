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
        $pontosColeta = $this->pointDAO->findAllNames();
        $mensagem = '';
        $tipoMensagem = '';

        include __DIR__ . '/../views/admin/donations.php';
    }

    /**
     * Recebe doação e adiciona ao inventário
     */
    public function receiveDonation(): void {
        $this->requireAdmin();

        $mensagem = '';
        $tipoMensagem = '';
        $filtro = 'todos';
        $busca = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receber_doacao'])) {
            $doacao_id = (int)($_POST['doacao_id'] ?? 0);
            $ponto_id = isset($_POST['ponto_id']) ? (int)$_POST['ponto_id'] : 0;

            if ($doacao_id <= 0) {
                $mensagem = 'Doação inválida.';
                $tipoMensagem = 'erro';
            } elseif ($ponto_id <= 0) {
                $mensagem = 'Selecione um ponto de coleta.';
                $tipoMensagem = 'erro';
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

                    $estoque_id = $this->inventoryDAO->getOrCreateEstoque($ponto_id);

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
                    $mensagem = 'Doação recebida e adicionada ao estoque com sucesso!';
                    $tipoMensagem = 'sucesso';
                } catch (Exception $e) {
                    Database::getInstance()->rollback();
                    $mensagem = 'Erro: ' . $e->getMessage();
                    $tipoMensagem = 'erro';
                }
            }
        }

        $doacoes = $this->donationDAO->findAll($filtro, $busca);
        $pontosColeta = $this->pointDAO->findAllNames();
        include __DIR__ . '/../views/admin/donations.php';
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
