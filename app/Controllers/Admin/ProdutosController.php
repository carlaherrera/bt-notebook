<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Security;
use PDO;

class ProdutosController extends Controller
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
        $statusFiltro = strtolower(trim($_GET['status_validade'] ?? ''));

        $produtos = $this->db->query("SELECT * FROM " . Database::table('produtos') . " ORDER BY nome")
            ->fetchAll(PDO::FETCH_ASSOC);

        $produtos = array_map(function ($p) {
            $statusValidade = 'indefinido';
            if (!empty($p['expira_em'])) {
                $exp = strtotime((string)$p['expira_em']);
                if ($exp !== false) {
                    $dias = (int)floor(($exp - time()) / 86400);
                    if ($dias < 0) {
                        $statusValidade = 'vencido';
                    } elseif ($dias <= 30) {
                        $statusValidade = 'proximo';
                    } else {
                        $statusValidade = 'valido';
                    }
                }
            }
            $p['status_validade'] = $statusValidade;
            return $p;
        }, $produtos);

        if (in_array($statusFiltro, ['valido', 'proximo', 'vencido'], true)) {
            $produtos = array_values(array_filter($produtos, fn($p) => ($p['status_validade'] ?? 'indefinido') === $statusFiltro));
        }

        $resumo = [
            'ativos' => count(array_filter($produtos, fn($p) => strtolower((string)($p['status'] ?? '')) === 'ativo')),
            'criticos' => count(array_filter($produtos, fn($p) => in_array(strtolower((string)($p['status'] ?? '')), ['critico', 'alerta'], true))),
            'itens_loja' => array_sum(array_column($produtos, 'estoque_loja')),
            'itens_consignado' => array_sum(array_column($produtos, 'estoque_consignado')),
            'status_val' => $statusFiltro,
            'val_validos' => count(array_filter($produtos, fn($p) => ($p['status_validade'] ?? '') === 'valido')),
            'val_proximos' => count(array_filter($produtos, fn($p) => ($p['status_validade'] ?? '') === 'proximo')),
            'val_vencidos' => count(array_filter($produtos, fn($p) => ($p['status_validade'] ?? '') === 'vencido')),
        ];

        $this->layout('layouts/painel', 'admin/produtos/index', [
            'produtos' => $produtos,
            'resumo' => $resumo,
        ]);
    }

    public function create(): void
    {
        $this->layout('layouts/painel', 'admin/produtos/novo', []);
    }

    public function store(): void
    {
        $this->validateCsrf();

        $nome = Security::sanitizeString($_POST['nome'] ?? '');
        $sku = Security::sanitizeString($_POST['sku'] ?? '');
        $categoria = Security::sanitizeString($_POST['categoria'] ?? '');
        $preco = (float)($_POST['preco'] ?? 0);
        $estoqueLoja = (int)($_POST['estoque_loja'] ?? 0);
        $estoqueConsig = (int)($_POST['estoque_consignado'] ?? 0);
        $minimo = (int)($_POST['minimo'] ?? 0);
        $status = Security::sanitizeString($_POST['status'] ?? 'ativo');
        $fabricadoEm = Security::sanitizeString($_POST['fabricado_em'] ?? '');
        $expiraEm = Security::sanitizeString($_POST['expira_em'] ?? '');
        $lote = Security::sanitizeString($_POST['lote'] ?? '');
        $observacoes = Security::sanitizeString($_POST['observacoes'] ?? '');

        if (!$nome || !$sku) {
            http_response_code(400);
            exit('Nome e SKU são obrigatórios.');
        }

        // Validação de datas (fabricado <= expira)
        if ($fabricadoEm && $expiraEm) {
            $fab = strtotime($fabricadoEm);
            $exp = strtotime($expiraEm);
            if ($fab !== false && $exp !== false && $fab > $exp) {
                http_response_code(400);
                exit('Data de fabricação não pode ser maior que a validade.');
            }
        }

        // Valida SKU único
        $stmtSku = $this->db->prepare("SELECT id FROM " . Database::table('produtos') . " WHERE sku = :sku LIMIT 1");
        $stmtSku->execute(['sku' => $sku]);
        if ($stmtSku->fetchColumn()) {
            http_response_code(400);
            exit('SKU já cadastrado.');
        }

        $stmt = $this->db->prepare(
            "INSERT INTO " . Database::table('produtos') . "
            (nome, sku, categoria, preco, estoque_loja, estoque_consignado, minimo, status, foto, fabricado_em, expira_em, lote, observacoes)
            VALUES (:nome, :sku, :categoria, :preco, :estoque_loja, :estoque_consignado, :minimo, :status, :foto, :fabricado_em, :expira_em, :lote, :observacoes)"
        );
        $stmt->execute([
            'nome' => $nome,
            'sku' => $sku,
            'categoria' => $categoria,
            'preco' => $preco,
            'estoque_loja' => $estoqueLoja,
            'estoque_consignado' => $estoqueConsig,
            'minimo' => $minimo,
            'status' => $status,
            'foto' => null,
            'fabricado_em' => $fabricadoEm ?: null,
            'expira_em' => $expiraEm ?: null,
            'lote' => $lote ?: null,
            'observacoes' => $observacoes ?: null,
        ]);

        $id = (int)$this->db->lastInsertId();
        $this->redirect('/admin/produtos/' . $id . '/ver');
    }

    public function show($id): void
    {
        $id = (int)$id;
        \App\Core\Logger::debug('ProdutosController::show iniciado', ['id' => $id]);
        
        $stmt = $this->db->prepare("SELECT * FROM " . Database::table('produtos') . " WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produto) {
            \App\Core\Logger::warning('Produto não encontrado', ['id' => $id]);
            http_response_code(404);
            exit('Produto não encontrado');
        }
        
        \App\Core\Logger::debug('Produto encontrado', ['id' => $id, 'produto' => $produto['nome'] ?? 'sem nome']);

        $statusValidade = 'indefinido';
        if (!empty($produto['expira_em'])) {
            $exp = strtotime((string)$produto['expira_em']);
            if ($exp !== false) {
                $dias = (int)floor(($exp - time()) / 86400);
                if ($dias < 0) {
                    $statusValidade = 'vencido';
                } elseif ($dias <= 30) {
                    $statusValidade = 'proximo';
                } else {
                    $statusValidade = 'valido';
                }
            }
        }

        $analytics = [
            'estoque_total' => ($produto['estoque_loja'] ?? 0) + ($produto['estoque_consignado'] ?? 0),
            'status' => $produto['status'] ?? 'ativo',
            'vendido_mes' => 0,
            'ticket_medio' => null,
            'status_validade' => $statusValidade,
            'expira_em' => $produto['expira_em'] ?? null,
        ];

        // Movimentações gerais para este produto
        $movs = [];
        $stmtMov = $this->db->prepare(
            "SELECT m.tipo, m.observacao AS descricao, m.quantidade, COALESCE(p.nome,'Loja') AS local, m.datahora AS data
             FROM " . Database::table('movimentacoes') . " m
             LEFT JOIN " . Database::table('parceiros') . " p ON p.id = m.parceiro_id
             WHERE m.produto = :produto
             ORDER BY m.datahora DESC
             LIMIT 50"
        );
        $stmtMov->execute(['produto' => $produto['nome']]);
        $movs = $stmtMov->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // Movimentações consignado para este produto
        $stmtConsig = $this->db->prepare(
            "SELECT cm.tipo, cm.descricao, cm.quantidade, COALESCE(p.nome,'Parceiro') AS local, cm.data_mov AS data
             FROM " . Database::table('consignado_movimentacoes') . " cm
             LEFT JOIN " . Database::table('parceiros') . " p ON p.id = cm.parceiro_id
             WHERE cm.produto = :produto
             ORDER BY cm.data_mov DESC
             LIMIT 50"
        );
        $stmtConsig->execute(['produto' => $produto['nome']]);
        $movsConsig = $stmtConsig->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $movimentacoes = array_merge($movs, $movsConsig);
        usort($movimentacoes, fn($a, $b) => strcmp($b['data'] ?? '', $a['data'] ?? ''));

        // Top parceiros por vendas consignadas
        $stmtTop = $this->db->prepare(
            "SELECT p.nome, p.cidade, SUM(cm.quantidade) AS vendido
             FROM " . Database::table('consignado_movimentacoes') . " cm
             LEFT JOIN " . Database::table('parceiros') . " p ON p.id = cm.parceiro_id
             WHERE cm.produto = :produto AND cm.tipo LIKE 'venda%'
             GROUP BY cm.parceiro_id
             ORDER BY vendido DESC
             LIMIT 3"
        );
        $stmtTop->execute(['produto' => $produto['nome']]);
        $parceirosTop = $stmtTop->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // Vendas mês (movimentações consignado tipo venda)
        $analytics['vendido_mes'] = array_sum(array_map(function ($m) {
            $tipo = strtolower((string)($m['tipo'] ?? ''));
            return strpos($tipo, 'venda') !== false ? (int)($m['quantidade'] ?? 0) : 0;
        }, $movsConsig));

        $this->layout('layouts/painel', 'admin/produtos/show', [
            'produto' => $produto,
            'parceirosTop' => $parceirosTop,
            'movimentacoes' => $movimentacoes,
            'analytics' => $analytics,
        ]);
    }

    public function edit($id): void
    {
        $id = (int)$id;
        $stmt = $this->db->prepare("SELECT * FROM " . Database::table('produtos') . " WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$produto) {
            http_response_code(404);
            exit('Produto não encontrado');
        }

        $this->layout('layouts/painel', 'admin/produtos/editar', [
            'produto' => $produto,
        ]);
    }

    public function update($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;

        $nome = Security::sanitizeString($_POST['nome'] ?? '');
        $sku = Security::sanitizeString($_POST['sku'] ?? '');
        $categoria = Security::sanitizeString($_POST['categoria'] ?? '');
        $preco = (float)($_POST['preco'] ?? 0);
        $estoqueLoja = (int)($_POST['estoque_loja'] ?? 0);
        $estoqueConsig = (int)($_POST['estoque_consignado'] ?? 0);
        $minimo = (int)($_POST['minimo'] ?? 0);
        $status = Security::sanitizeString($_POST['status'] ?? 'ativo');
        $fabricadoEm = Security::sanitizeString($_POST['fabricado_em'] ?? '');
        $expiraEm = Security::sanitizeString($_POST['expira_em'] ?? '');
        $lote = Security::sanitizeString($_POST['lote'] ?? '');
        $observacoes = Security::sanitizeString($_POST['observacoes'] ?? '');

        if (!$nome || !$sku) {
            http_response_code(400);
            exit('Nome e SKU são obrigatórios.');
        }

        if ($fabricadoEm && $expiraEm) {
            $fab = strtotime($fabricadoEm);
            $exp = strtotime($expiraEm);
            if ($fab !== false && $exp !== false && $fab > $exp) {
                http_response_code(400);
                exit('Data de fabricação não pode ser maior que a validade.');
            }
        }

        $stmt = $this->db->prepare(
            "UPDATE " . Database::table('produtos') . "
             SET nome = :nome, sku = :sku, categoria = :categoria, preco = :preco,
                 estoque_loja = :estoque_loja, estoque_consignado = :estoque_consignado,
                 minimo = :minimo, status = :status,
                 fabricado_em = :fabricado_em, expira_em = :expira_em, lote = :lote, observacoes = :observacoes
             WHERE id = :id"
        );
        $stmt->execute([
            'id' => $id,
            'nome' => $nome,
            'sku' => $sku,
            'categoria' => $categoria,
            'preco' => $preco,
            'estoque_loja' => $estoqueLoja,
            'estoque_consignado' => $estoqueConsig,
            'minimo' => $minimo,
            'status' => $status,
            'fabricado_em' => $fabricadoEm ?: null,
            'expira_em' => $expiraEm ?: null,
            'lote' => $lote ?: null,
            'observacoes' => $observacoes ?: null,
        ]);

        $this->redirect('/admin/produtos/' . $id . '/ver');
    }

    public function toggle($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;
        $stmt = $this->db->prepare("SELECT status FROM " . Database::table('produtos') . " WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $status = $stmt->fetchColumn();
        if ($status === false) {
            http_response_code(404);
            exit('Produto não encontrado');
        }

        $novoStatus = strtolower((string)$status) === 'inativo' ? 'ativo' : 'inativo';
        $upd = $this->db->prepare("UPDATE " . Database::table('produtos') . " SET status = :status WHERE id = :id");
        $upd->execute(['status' => $novoStatus, 'id' => $id]);

        $this->redirect('/admin/produtos');
    }

    public function destroy($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;
        $del = $this->db->prepare("DELETE FROM " . Database::table('produtos') . " WHERE id = :id");
        $del->execute(['id' => $id]);
        $this->redirect('/admin/produtos');
    }
}
