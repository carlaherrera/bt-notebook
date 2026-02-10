<?php

namespace App\Controllers\Cliente;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Security;
use PDO;

class PedidosController extends Controller
{
    private PDO $db;

    public function __construct()
    {
        $this->requireRole('cliente');
        $this->requireActive();
        $this->db = Database::getConnection();
    }

    public function index(): void
    {
        $userId = Auth::user()?->id;
        $stmt = $this->db->prepare(
            "SELECT p.id, p.status, p.subtotal, p.frete, p.total, p.created_at
             FROM " . Database::table('pedidos') . " p
             WHERE p.user_id = :user_id
             ORDER BY p.created_at DESC"
        );
        $stmt->execute(['user_id' => $userId]);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->layout('layouts/painel', 'cliente/pedidos/index', [
            'pedidos' => $pedidos,
        ]);
    }

    public function create(): void
    {
        $stmt = $this->db->query("SELECT id, nome, preco FROM " . Database::table('produtos') . " ORDER BY nome");
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $userId = Auth::user()?->id;
        $stmt = $this->db->prepare(
            "SELECT id, titulo, linha1, linha2, cidade, estado, cep FROM " . Database::table('enderecos') . " WHERE user_id = :user_id"
        );
        $stmt->execute(['user_id' => $userId]);
        $enderecos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->db->prepare(
            "SELECT id, tipo, apelido, masked FROM " . Database::table('metodos_pagamento') . " WHERE user_id = :user_id"
        );
        $stmt->execute(['user_id' => $userId]);
        $pagamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->layout('layouts/painel', 'cliente/pedidos/novo', [
            'produtos' => $produtos,
            'enderecos' => $enderecos,
            'pagamentos' => $pagamentos,
        ]);
    }

    public function store(): void
    {
        $this->validateCsrf();
        $userId = Auth::user()?->id;

        $endereco_id = (int)($_POST['endereco_id'] ?? 0);
        $pagamento_id = (int)($_POST['pagamento_id'] ?? 0);
        $itens = $_POST['itens'] ?? [];

        if (!$endereco_id || !$pagamento_id || empty($itens)) {
            http_response_code(400);
            exit('Dados inválidos para criar pedido');
        }

        $subtotal = 0;
        foreach ($itens as $item) {
            $produto_id = (int)($item['produto_id'] ?? 0);
            $qtd = (int)($item['qtd'] ?? 0);
            if ($produto_id > 0 && $qtd > 0) {
                $stmt = $this->db->prepare("SELECT preco FROM " . Database::table('produtos') . " WHERE id = :id");
                $stmt->execute(['id' => $produto_id]);
                $produto = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($produto) {
                    $subtotal += $produto['preco'] * $qtd;
                }
            }
        }

        $frete = 15.00;
        $total = $subtotal + $frete;

        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO " . Database::table('pedidos') . "
                (user_id, endereco_id, pagamento_id, status, subtotal, frete, total, created_at)
                VALUES (:user_id, :endereco_id, :pagamento_id, :status, :subtotal, :frete, :total, :created_at)"
            );
            $stmt->execute([
                'user_id' => $userId,
                'endereco_id' => $endereco_id,
                'pagamento_id' => $pagamento_id,
                'status' => 'criado',
                'subtotal' => $subtotal,
                'frete' => $frete,
                'total' => $total,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $pedido_id = (int)$this->db->lastInsertId();

            foreach ($itens as $item) {
                $produto_id = (int)($item['produto_id'] ?? 0);
                $qtd = (int)($item['qtd'] ?? 0);
                if ($produto_id > 0 && $qtd > 0) {
                    $stmt = $this->db->prepare("SELECT nome, preco FROM " . Database::table('produtos') . " WHERE id = :id");
                    $stmt->execute(['id' => $produto_id]);
                    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($produto) {
                        $stmt = $this->db->prepare(
                            "INSERT INTO " . Database::table('pedido_itens') . "
                            (pedido_id, produto_id, nome_snapshot, qtd, preco_unitario, total_linha)
                            VALUES (:pedido_id, :produto_id, :nome, :qtd, :preco, :total)"
                        );
                        $stmt->execute([
                            'pedido_id' => $pedido_id,
                            'produto_id' => $produto_id,
                            'nome' => $produto['nome'],
                            'qtd' => $qtd,
                            'preco' => $produto['preco'],
                            'total' => $produto['preco'] * $qtd,
                        ]);
                    }
                }
            }

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }

        $this->redirect('/cliente/pedidos?ok=1');
    }

    public function show($id): void
    {
        $id = (int)$id;
        $userId = Auth::user()?->id;

        $stmt = $this->db->prepare(
            "SELECT p.* FROM " . Database::table('pedidos') . " p
             WHERE p.id = :id AND p.user_id = :user_id"
        );
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pedido) {
            http_response_code(404);
            exit('Pedido não encontrado');
        }

        $stmt = $this->db->prepare(
            "SELECT * FROM " . Database::table('pedido_itens') . " WHERE pedido_id = :pedido_id"
        );
        $stmt->execute(['pedido_id' => $id]);
        $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->layout('layouts/painel', 'cliente/pedidos/show', [
            'pedido' => $pedido,
            'itens' => $itens,
        ]);
    }

    public function cancel($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;
        $userId = Auth::user()?->id;

        $stmt = $this->db->prepare(
            "SELECT status FROM " . Database::table('pedidos') . " WHERE id = :id AND user_id = :user_id"
        );
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pedido) {
            http_response_code(404);
            exit('Pedido não encontrado');
        }

        if ($pedido['status'] !== 'criado') {
            http_response_code(400);
            exit('Apenas pedidos em status "criado" podem ser cancelados');
        }

        $stmt = $this->db->prepare(
            "UPDATE " . Database::table('pedidos') . " SET status = :status WHERE id = :id"
        );
        $stmt->execute(['status' => 'cancelado', 'id' => $id]);

        $this->redirect('/cliente/pedidos?ok=1');
    }
}
