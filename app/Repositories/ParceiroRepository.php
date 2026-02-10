<?php
// /app/Repositories/ParceiroRepository.php
// Acesso a dados de parceiros e consignado

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

class ParceiroRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function allComResumo(): array
    {
        $sql = "SELECT p.id, p.usuario_id, p.nome, p.tipo, p.documento, p.cidade,
                       p.contato, p.telefone, p.email, p.status, p.ticket_medio, p.atualizado_em,
                       u.nome AS user_nome, u.sobrenome AS user_sobrenome, u.email AS user_email,
                       COALESCE(SUM(cp.estoque), 0) AS itens,
                       COALESCE(SUM(cp.devolucao), 0) AS devolucao,
                       COALESCE(SUM(cp.vendido_mes), 0) AS vendas_mes,
                       COALESCE(SUM(CASE WHEN cp.estoque <= cp.minimo THEN 1 ELSE 0 END), 0) AS baixo,
                       COALESCE(SUM(CASE WHEN cm.tipo = 'transferencia' THEN cm.quantidade ELSE 0 END), 0) AS transferencias_mes
                FROM " . Database::table('parceiros') . " p
                LEFT JOIN " . Database::table('usuarios') . " u ON u.id = p.usuario_id
                LEFT JOIN " . Database::table('consignado_produtos') . " cp ON cp.parceiro_id = p.id
                LEFT JOIN " . Database::table('consignado_movimentacoes') . " cm ON cm.parceiro_id = p.id
                GROUP BY p.id
                ORDER BY p.nome";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findComResumo(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, u.nome AS user_nome, u.sobrenome AS user_sobrenome, u.email AS user_email,
                    COALESCE(SUM(cp.estoque), 0) AS itens,
                    COALESCE(SUM(cp.devolucao), 0) AS devolucao,
                    COALESCE(SUM(cp.vendido_mes), 0) AS vendas_mes,
                    COALESCE(SUM(CASE WHEN cp.estoque <= cp.minimo THEN 1 ELSE 0 END), 0) AS itens_baixo
             FROM " . Database::table('parceiros') . " p
             LEFT JOIN " . Database::table('usuarios') . " u ON u.id = p.usuario_id
             LEFT JOIN " . Database::table('consignado_produtos') . " cp ON cp.parceiro_id = p.id
             WHERE p.id = :id
             GROUP BY p.id"
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function produtos(int $parceiroId): array
    {
        $stmt = $this->db->prepare(
            "SELECT produto, sku, lote, nf, estoque, min, vendido_mes, devolucao, prazo_dev
             FROM " . Database::table('consignado_produtos') . "
             WHERE parceiro_id = :id"
        );
        $stmt->execute(['id' => $parceiroId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function movimentacoesConsignado(int $parceiroId, int $limit = 50): array
    {
        $stmt = $this->db->prepare(
            "SELECT tipo, descricao, quantidade, data_mov AS data, created_at
             FROM " . Database::table('consignado_movimentacoes') . "
             WHERE parceiro_id = :id
             ORDER BY data_mov DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':id', $parceiroId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function resumoGeral(): array
    {
        $stmt = $this->db->query(
            "SELECT 
                COALESCE(SUM(cp.estoque),0) AS total_itens,
                COALESCE(SUM(CASE WHEN cp.estoque <= cp.minimo THEN 1 ELSE 0 END),0) AS itens_alerta,
                COALESCE(SUM(cp.vendido_mes),0) AS vendas_mes,
                COALESCE(SUM(CASE WHEN cm.tipo = 'transferencia' THEN cm.quantidade ELSE 0 END),0) AS transferencias_mes
             FROM " . Database::table('parceiros') . " p
             LEFT JOIN " . Database::table('consignado_produtos') . " cp ON cp.parceiro_id = p.id
             LEFT JOIN " . Database::table('consignado_movimentacoes') . " cm ON cm.parceiro_id = p.id"
        );
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }
}
