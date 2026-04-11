<?php

require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../dto/CampaignDTO.php';

/**
 * Data Access Object para gerenciar operações de campanhas no banco de dados.
 * Compatível com o schema atual: campanha + necessidade.
 */
class CampaignDAO {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    /**
     * Busca todas as campanhas ativas.
     * @return array Array de CampaignDTO
     */
    public function findAllActive(): array {
        $sql = '
            SELECT id_campanha AS id, titulo, descricao, data_inicio, data_fim, status, id_usuario
            FROM campanha
            WHERE status = \'ATIVA\'
            ORDER BY titulo ASC
        ';
        $resultado = $this->conn->query($sql);

        if (!$resultado) {
            error_log('Erro ao buscar campanhas ativas: ' . $this->conn->error);
            return [];
        }

        $campanhas = [];
        while ($dados = $resultado->fetch_assoc()) {
            $dados['status'] = strtoupper((string)$dados['status']);
            $campanhas[] = CampaignDTO::fromArray($dados);
        }

        return $campanhas;
    }

    /**
     * Busca campanha por ID com suas necessidades.
     * @param int $id
     * @return CampaignDTO|null
     */
    public function findById(int $id): ?CampaignDTO {
        $sql = '
            SELECT id_campanha AS id, titulo, descricao, data_inicio, data_fim, status, id_usuario
            FROM campanha
            WHERE id_campanha = ?
            LIMIT 1
        ';
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log('Erro ao preparar busca de campanha: ' . $this->conn->error);
            return null;
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $dados = $resultado->fetch_assoc();
            $stmt->close();

            $campanha = CampaignDTO::fromArray($dados);
            $campanha->necessidades = $this->getNecessidades($id);
            return $campanha;
        }

        $stmt->close();
        return null;
    }

    /**
     * Busca campanha por título.
     * @param string $titulo
     * @return CampaignDTO|null
     */
    public function findByTitle(string $titulo): ?CampaignDTO {
        $sql = '
            SELECT id_campanha AS id, titulo, descricao, data_inicio, data_fim, status, id_usuario
            FROM campanha
            WHERE titulo = ? AND status = \'ATIVA\'
            LIMIT 1
        ';
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log('Erro ao preparar busca por título: ' . $this->conn->error);
            return null;
        }

        $stmt->bind_param('s', $titulo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $dados = $resultado->fetch_assoc();
            $stmt->close();

            $campanha = CampaignDTO::fromArray($dados);
            $campanha->necessidades = $this->getNecessidades((int)$dados['id']);
            return $campanha;
        }

        $stmt->close();
        return null;
    }

    /**
     * Busca necessidades de uma campanha.
     * @param int $campanha_id
     * @return array
     */
    public function getNecessidades(int $campanha_id): array {
        $sql = '
            SELECT n.id_necessidade AS id, n.id_categoria AS categoria_id, ci.nome AS categoria_nome,
                   n.quantidade_necessaria, n.descricao
            FROM necessidade n
            INNER JOIN categoria_item ci ON n.id_categoria = ci.id_categoria
            WHERE n.id_campanha = ?
            ORDER BY ci.nome ASC
        ';
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log('Erro ao preparar busca de necessidades: ' . $this->conn->error);
            return [];
        }

        $stmt->bind_param('i', $campanha_id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $necessidades = [];
        while ($item = $resultado->fetch_assoc()) {
            $necessidades[] = $item;
        }

        $stmt->close();
        return $necessidades;
    }

    /**
     * Busca todas as campanhas.
     * @return array
     */
    public function findAll(): array {
        $sql = '
            SELECT id_campanha AS id, titulo, descricao, data_inicio, data_fim, status, id_usuario
            FROM campanha
            ORDER BY titulo ASC
        ';
        $resultado = $this->conn->query($sql);

        if (!$resultado) {
            error_log('Erro ao buscar todas as campanhas: ' . $this->conn->error);
            return [];
        }

        $campanhas = [];
        while ($dados = $resultado->fetch_assoc()) {
            $dados['status'] = strtoupper((string)$dados['status']);
            $campanhas[] = CampaignDTO::fromArray($dados);
        }

        return $campanhas;
    }
}
