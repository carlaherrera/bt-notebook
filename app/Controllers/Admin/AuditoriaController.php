<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Security;
use PDO;

class AuditoriaController extends Controller
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
        // Resumo
        $driver = $this->db->getAttribute(PDO::ATTR_DRIVER_NAME);
        $sqlMes = $driver === 'mysql'
            ? "SELECT COUNT(*) FROM " . Database::table('auditorias') . " WHERE DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')"
            : "SELECT COUNT(*) FROM " . Database::table('auditorias') . " WHERE strftime('%Y-%m', created_at) = strftime('%Y-%m', 'now')";

        $resumo = [
            'contagens_pendentes' => (int)$this->db->query("SELECT COUNT(*) FROM " . Database::table('auditoria_itens') . " WHERE status = 'pendente'")->fetchColumn(),
            'divergencias' => (int)$this->db->query("SELECT COUNT(*) FROM " . Database::table('auditoria_itens') . " WHERE status = 'divergencia'")->fetchColumn(),
            'ajustes_aplicados' => (int)$this->db->query("SELECT COUNT(*) FROM " . Database::table('auditoria_historico') . " WHERE LOWER(acao) LIKE '%ajuste%'")->fetchColumn(),
            'auditorias_mes' => (int)$this->db->query($sqlMes)->fetchColumn(),
        ];

        $auditorias = $this->db->query(
            "SELECT id, status, descricao, created_at, updated_at FROM " . Database::table('auditorias') . " ORDER BY created_at DESC"
        )->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // Checklist (itens de auditoria)
        $stmtItens = $this->db->query(
            "SELECT produto, local, qtde_sistema, qtde_fisica, status
             FROM " . Database::table('auditoria_itens') . "
             ORDER BY status DESC, id DESC
             LIMIT 30"
        );
        $checklist = $stmtItens->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // Histórico
        $stmtHist = $this->db->query(
            "SELECT acao, descricao, usuario, data
             FROM " . Database::table('auditoria_historico') . "
             ORDER BY data DESC
             LIMIT 30"
        );
        $historico = $stmtHist->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $this->layout('layouts/painel', 'admin/auditoria/index', [
            'resumo' => $resumo,
            'checklist' => $checklist,
            'historico' => $historico,
            'auditorias' => $auditorias,
        ]);
    }

    public function create(): void
    {
        $this->layout('layouts/painel', 'admin/auditoria/novo', [
            'produtos' => $this->carregarProdutos(),
            'parceiros' => $this->carregarParceiros(),
        ]);
    }

    public function store(): void
    {
        $this->validateCsrf();
        $status = Security::sanitizeString($_POST['status'] ?? 'pendente');
        $descricao = Security::sanitizeString($_POST['descricao'] ?? '');
        $itemProduto = Security::sanitizeString($_POST['item_produto'] ?? '');
        $itemLocal = Security::sanitizeString($_POST['item_local'] ?? '');
        $itemQtdeSistema = (int)($_POST['item_qtde_sistema'] ?? 0);
        $itemQtdeFisica = $_POST['item_qtde_fisica'] === '' ? null : (int)($_POST['item_qtde_fisica']);
        $itemStatus = Security::sanitizeString($_POST['item_status'] ?? 'pendente');

        $stmt = $this->db->prepare(
            "INSERT INTO " . Database::table('auditorias') . " (status, descricao) VALUES (:status, :descricao)"
        );
        $stmt->execute([
            'status' => $status ?: 'pendente',
            'descricao' => $descricao,
        ]);

        $id = (int)$this->db->lastInsertId();

        // item inicial opcional
        if ($itemProduto) {
            $stmtItem = $this->db->prepare(
                "INSERT INTO " . Database::table('auditoria_itens') . " (auditoria_id, produto, local, qtde_sistema, qtde_fisica, status)
                 VALUES (:auditoria_id, :produto, :local, :qtde_sistema, :qtde_fisica, :status)"
            );
            $stmtItem->execute([
                'auditoria_id' => $id,
                'produto' => $itemProduto,
                'local' => $itemLocal,
                'qtde_sistema' => $itemQtdeSistema,
                'qtde_fisica' => $itemQtdeFisica,
                'status' => $itemStatus ?: 'pendente',
            ]);
        }

        $this->redirect('/admin/auditoria/' . $id . '/editar');
    }

    public function edit($id): void
    {
        $id = (int)$id;
        $stmt = $this->db->prepare("SELECT * FROM " . Database::table('auditorias') . " WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $auditoria = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$auditoria) {
            http_response_code(404);
            exit('Auditoria não encontrada');
        }

        $itens = $this->db->prepare(
            "SELECT id, produto, local, qtde_sistema, qtde_fisica, status, created_at
             FROM " . Database::table('auditoria_itens') . " WHERE auditoria_id = :id ORDER BY id DESC"
        );
        $itens->execute(['id' => $id]);
        $listaItens = $itens->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $this->layout('layouts/painel', 'admin/auditoria/editar', [
            'auditoria' => $auditoria,
            'itens' => $listaItens,
            'produtos' => $this->carregarProdutos(),
            'parceiros' => $this->carregarParceiros(),
        ]);
    }

    public function update($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;

        $stmt = $this->db->prepare("SELECT id FROM " . Database::table('auditorias') . " WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        if (!$stmt->fetchColumn()) {
            http_response_code(404);
            exit('Auditoria não encontrada');
        }

        $status = Security::sanitizeString($_POST['status'] ?? 'pendente');
        $descricao = Security::sanitizeString($_POST['descricao'] ?? '');

        // item adicional opcional
        $itemProduto = Security::sanitizeString($_POST['item_produto'] ?? '');
        $itemLocal = Security::sanitizeString($_POST['item_local'] ?? '');
        $itemQtdeSistema = (int)($_POST['item_qtde_sistema'] ?? 0);
        $itemQtdeFisica = $_POST['item_qtde_fisica'] === '' ? null : (int)($_POST['item_qtde_fisica']);
        $itemStatus = Security::sanitizeString($_POST['item_status'] ?? 'pendente');

        $upd = $this->db->prepare(
            "UPDATE " . Database::table('auditorias') . " SET status = :status, descricao = :descricao WHERE id = :id"
        );
        $upd->execute([
            'id' => $id,
            'status' => $status ?: 'pendente',
            'descricao' => $descricao,
        ]);

        if ($itemProduto) {
            $stmtItem = $this->db->prepare(
                "INSERT INTO " . Database::table('auditoria_itens') . " (auditoria_id, produto, local, qtde_sistema, qtde_fisica, status)
                 VALUES (:auditoria_id, :produto, :local, :qtde_sistema, :qtde_fisica, :status)"
            );
            $stmtItem->execute([
                'auditoria_id' => $id,
                'produto' => $itemProduto,
                'local' => $itemLocal,
                'qtde_sistema' => $itemQtdeSistema,
                'qtde_fisica' => $itemQtdeFisica,
                'status' => $itemStatus ?: 'pendente',
            ]);
        }

        $this->redirect('/admin/auditoria');
    }

    public function destroy($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;
        $del = $this->db->prepare("DELETE FROM " . Database::table('auditorias') . " WHERE id = :id");
        $del->execute(['id' => $id]);
        $this->redirect('/admin/auditoria');
    }

    private function carregarProdutos(): array
    {
        try {
            $stmt = $this->db->query("SELECT id, nome FROM " . Database::table('produtos') . " ORDER BY nome");
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function carregarParceiros(): array
    {
        try {
            $stmt = $this->db->query("SELECT id, nome FROM " . Database::table('parceiros') . " ORDER BY nome");
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (\Throwable $e) {
            return [];
        }
    }
}
