<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Security;
use PDO;

class MovimentacoesController extends Controller
{
    private PDO $db;

    public function __construct()
    {
        $this->requireRole('admin');
        $this->requireActive();
        $this->db = Database::getConnection();
    }

    public function index(): void
    {
        $filtros = [
            'tipo' => $_GET['tipo'] ?? null,
            'parceiro' => $_GET['parceiro'] ?? null,
            'produto' => $_GET['produto'] ?? null,
            'dias' => isset($_GET['dias']) ? (int)$_GET['dias'] : 30,
            'ok' => isset($_GET['ok']),
        ];

        $linhas = $this->carregarMovimentacoes($filtros);

        $resumo = [
            'entradas' => array_sum(array_map(fn($m) => strtolower((string)($m['tipo'] ?? '')) === 'entrada' ? (int)($m['quantidade'] ?? 0) : 0, $linhas)),
            'transferencias' => array_sum(array_map(fn($m) => strtolower((string)($m['tipo'] ?? '')) === 'transferencia' ? (int)($m['quantidade'] ?? 0) : 0, $linhas)),
            'vendas' => array_sum(array_map(function ($m) {
                $tipo = strtolower((string)($m['tipo'] ?? ''));
                return strpos($tipo, 'venda') !== false ? (int)($m['quantidade'] ?? 0) : 0;
            }, $linhas)),
            'devolucoes' => array_sum(array_map(fn($m) => strtolower((string)($m['tipo'] ?? '')) === 'devolucao' ? (int)($m['quantidade'] ?? 0) : 0, $linhas)),
        ];

        $this->layout('layouts/painel', 'admin/movimentacoes/index', [
            'resumo' => $resumo,
            'filtros' => $filtros,
            'linhas' => $linhas,
        ]);
    }

    public function nova(): void
    {
        $tipos = ['entrada', 'transferencia', 'venda', 'devolucao', 'ajuste'];
        $parceiros = $this->carregarParceiros();
        $produtos = $this->carregarProdutos();

        $this->layout('layouts/painel', 'admin/movimentacoes/nova', [
            'tipos' => $tipos,
            'parceiros' => $parceiros,
            'produtos' => $produtos,
        ]);
    }

    public function store(): void
    {
        $this->validateCsrf();

        $tipo = strtolower(Security::sanitizeString($_POST['tipo'] ?? ''));
        $parceiroId = isset($_POST['parceiro_id']) && $_POST['parceiro_id'] !== '' ? (int)$_POST['parceiro_id'] : null;
        $produto = Security::sanitizeString($_POST['produto'] ?? '');
        $quantidade = (int)($_POST['quantidade'] ?? 0);
        $nfRef = Security::sanitizeString($_POST['nf_ref'] ?? '');
        $lote = Security::sanitizeString($_POST['lote'] ?? '');
        $datahora = $_POST['datahora'] ?? date('Y-m-d H:i:s');
        $observacao = Security::sanitizeString($_POST['observacao'] ?? '');

        $tiposPermitidos = ['entrada', 'transferencia', 'venda', 'devolucao', 'ajuste'];
        if (!in_array($tipo, $tiposPermitidos, true)) {
            http_response_code(400);
            exit('Tipo inválido.');
        }

        // Normaliza datetime-local (com "T") para formato aceito pelo DB
        if (str_contains($datahora, 'T')) {
            $datahora = str_replace('T', ' ', $datahora);
        }

        if (!$tipo || !$produto || !$quantidade || !$datahora) {
            http_response_code(400);
            exit('Campos obrigatórios não preenchidos.');
        }

        $stmt = $this->db->prepare(
            "INSERT INTO " . Database::table('movimentacoes') . "
            (tipo, parceiro_id, produto, quantidade, nf_ref, lote, datahora, observacao)
            VALUES (:tipo, :parceiro_id, :produto, :quantidade, :nf_ref, :lote, :datahora, :observacao)"
        );

        $stmt->execute([
            'tipo' => $tipo,
            'parceiro_id' => $parceiroId,
            'produto' => $produto,
            'quantidade' => $quantidade,
            'nf_ref' => $nfRef,
            'lote' => $lote,
            'datahora' => $datahora,
            'observacao' => $observacao,
        ]);

        $this->redirect('/admin/movimentacoes?ok=1');
    }

    public function edit($id): void
    {
        $id = (int)$id;
        $stmt = $this->db->prepare("SELECT * FROM " . Database::table('movimentacoes') . " WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $mov = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$mov) {
            http_response_code(404);
            exit('Movimentação não encontrada');
        }

        $tipos = ['entrada', 'transferencia', 'venda', 'devolucao', 'ajuste'];
        $parceiros = $this->carregarParceiros();
        $produtos = $this->carregarProdutos();

        $this->layout('layouts/painel', 'admin/movimentacoes/editar', [
            'mov' => $mov,
            'tipos' => $tipos,
            'parceiros' => $parceiros,
            'produtos' => $produtos,
        ]);
    }

    public function update($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;
        $stmt = $this->db->prepare("SELECT id FROM " . Database::table('movimentacoes') . " WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        if (!$stmt->fetchColumn()) {
            http_response_code(404);
            exit('Movimentação não encontrada');
        }

        $tipo = strtolower(Security::sanitizeString($_POST['tipo'] ?? ''));
        $parceiroId = isset($_POST['parceiro_id']) && $_POST['parceiro_id'] !== '' ? (int)$_POST['parceiro_id'] : null;
        $produto = Security::sanitizeString($_POST['produto'] ?? '');
        $quantidade = (int)($_POST['quantidade'] ?? 0);
        $nfRef = Security::sanitizeString($_POST['nf_ref'] ?? '');
        $lote = Security::sanitizeString($_POST['lote'] ?? '');
        $datahora = $_POST['datahora'] ?? date('Y-m-d H:i:s');
        $observacao = Security::sanitizeString($_POST['observacao'] ?? '');

        $tiposPermitidos = ['entrada', 'transferencia', 'venda', 'devolucao', 'ajuste'];
        if (!in_array($tipo, $tiposPermitidos, true) || !$produto || !$quantidade || !$datahora) {
            http_response_code(400);
            exit('Dados inválidos.');
        }
        if (strpos($datahora, 'T') !== false) {
            $datahora = str_replace('T', ' ', $datahora);
        }

        $upd = $this->db->prepare(
            "UPDATE " . Database::table('movimentacoes') . "
             SET tipo = :tipo, parceiro_id = :parceiro_id, produto = :produto, quantidade = :quantidade,
                 nf_ref = :nf_ref, lote = :lote, datahora = :datahora, observacao = :observacao
             WHERE id = :id"
        );
        $upd->execute([
            'id' => $id,
            'tipo' => $tipo,
            'parceiro_id' => $parceiroId,
            'produto' => $produto,
            'quantidade' => $quantidade,
            'nf_ref' => $nfRef,
            'lote' => $lote,
            'datahora' => $datahora,
            'observacao' => $observacao,
        ]);

        $this->redirect('/admin/movimentacoes?ok=1');
    }

    public function destroy($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;
        $stmt = $this->db->prepare("DELETE FROM " . Database::table('movimentacoes') . " WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $this->redirect('/admin/movimentacoes?ok=1');
    }

    private function carregarParceiros(): array
    {
        $stmt = $this->db->query("SELECT id, nome FROM " . Database::table('parceiros') . " ORDER BY nome");
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function carregarProdutos(): array
    {
        $stmt = $this->db->query("SELECT DISTINCT produto FROM " . Database::table('consignado_produtos') . " ORDER BY produto");
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC) ?: [], 'produto');
    }

    private function carregarMovimentacoes(array $filtros): array
    {
        try {
            $sqlMov = "SELECT m.id, m.tipo, m.produto, m.quantidade, m.datahora AS data, p.nome AS parceiro, m.observacao AS descricao, 'geral' AS origem
                       FROM " . Database::table('movimentacoes') . " m
                       LEFT JOIN " . Database::table('parceiros') . " p ON p.id = m.parceiro_id";
            $movs = $this->db->query($sqlMov)->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (\Throwable $e) {
            $this->debug('carregarMovimentacoes (geral): ' . $e->getMessage());
            $movs = [];
        }

        try {
            $sqlConsig = "SELECT cm.id, cm.tipo, cm.produto, cm.quantidade, cm.data, p.nome AS parceiro, cm.descricao, 'consignado' AS origem
                          FROM " . Database::table('consignado_movimentacoes') . " cm
                          LEFT JOIN " . Database::table('parceiros') . " p ON p.id = cm.parceiro_id";
            $consig = $this->db->query($sqlConsig)->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (\Throwable $e) {
            $this->debug('carregarMovimentacoes (consignado): ' . $e->getMessage());
            $consig = [];
        }

        $todas = array_merge($movs, $consig);

        // Aplicar filtros simples em memória
        $limiteDias = $filtros['dias'] ?? 30;
        $limiteTimestamp = strtotime('-' . max(0, (int)$limiteDias) . ' days');
        $filtroTipo = $filtros['tipo'] ? strtolower((string)$filtros['tipo']) : null;
        $filtroParceiro = $filtros['parceiro'] ?? null;
        $filtroProduto = $filtros['produto'] ?? null;

        $todas = array_values(array_filter($todas, function ($m) use ($limiteTimestamp, $filtroTipo, $filtroParceiro, $filtroProduto) {
            $dataTs = isset($m['data']) ? strtotime($m['data']) : null;
            if ($dataTs && $limiteTimestamp && $dataTs < $limiteTimestamp) {
                return false;
            }
            if ($filtroTipo) {
                $tipo = strtolower((string)($m['tipo'] ?? ''));
                if (strpos($tipo, $filtroTipo) === false) {
                    return false;
                }
            }
            if ($filtroParceiro && isset($m['parceiro']) && stripos((string)$m['parceiro'], (string)$filtroParceiro) === false) {
                return false;
            }
            if ($filtroProduto && isset($m['produto']) && stripos((string)$m['produto'], (string)$filtroProduto) === false) {
                return false;
            }
            return true;
        }));

        usort($todas, function ($a, $b) {
            return strcmp($b['data'] ?? '', $a['data'] ?? '');
        });

        return $todas;
    }
}
