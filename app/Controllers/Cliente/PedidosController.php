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

        // Loga erros fatais no debug.log raiz
        register_shutdown_function(function () {
            $err = error_get_last();
            if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR], true)) {
                $payload = '[' . date('Y-m-d H:i:s') . "] PedidosController shutdown " . json_encode($err, JSON_UNESCAPED_UNICODE) . "\n";
                $path = __DIR__ . '/../../../debug.log';
                @file_put_contents($path, $payload, FILE_APPEND);
            }
        });
    }

    private function logDebug(string $message, array $context = []): void
    {
        \App\Core\Logger::debug($message, $context);
        $payload = '[' . date('Y-m-d H:i:s') . "] {$message} " . json_encode($context, JSON_UNESCAPED_UNICODE) . "\n";
        $path = __DIR__ . '/../../../debug.log';
        @file_put_contents($path, $payload, FILE_APPEND);
    }

    private function logError(string $message, array $context = []): void
    {
        \App\Core\Logger::error($message, $context);
        $payload = '[' . date('Y-m-d H:i:s') . "] {$message} " . json_encode($context, JSON_UNESCAPED_UNICODE) . "\n";
        $path = __DIR__ . '/../../../debug.log';
        @file_put_contents($path, $payload, FILE_APPEND);
    }

    public function index(): void
    {
        $userId = Auth::user()?->id;
        $statusFiltro = strtolower(trim($_GET['status'] ?? ''));
        $mapStatus = [
            'criado' => 'criado',
            'em_entrega' => 'em_entrega',
            'em-entrega' => 'em_entrega',
            'entrega' => 'em_entrega',
            'entregue' => 'entregue',
            'cancelado' => 'cancelado',
        ];
        if (isset($mapStatus[$statusFiltro])) {
            $statusFiltro = $mapStatus[$statusFiltro];
        }
        $statusPermitidos = ['criado', 'em_entrega', 'entregue', 'cancelado'];
        try {
            $this->logDebug('PedidosController@index', ['user_id' => $userId, 'status' => $statusFiltro]);
            $sql = "SELECT p.id, p.status, p.subtotal, p.frete, p.total, p.created_at
                    FROM " . Database::table('pedidos') . " p
                    WHERE p.user_id = :user_id";
            $params = ['user_id' => $userId];
            if (in_array($statusFiltro, $statusPermitidos, true)) {
                $sql .= " AND p.status = :status";
                $params['status'] = $statusFiltro;
            }
            $sql .= " ORDER BY p.created_at DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Carrega itens para cada pedido
            foreach ($pedidos as &$pedido) {
                $stmtItens = $this->db->prepare(
                    "SELECT nome_snapshot as nome, qtd, preco_unitario, total_linha FROM " . Database::table('pedido_itens') . " WHERE pedido_id = :pedido_id"
                );
                $stmtItens->execute(['pedido_id' => $pedido['id']]);
                $pedido['itens'] = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
            }

            $this->layout('layouts/painel', 'cliente/pedidos/index', [
                'pedidos' => $pedidos,
                'statusFiltro' => in_array($statusFiltro, $statusPermitidos, true) ? $statusFiltro : '',
            ]);
        } catch (\Throwable $e) {
            $this->logError('PedidosController@index erro', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    public function create(): void
    {
        try {
            $stmt = $this->db->query("SELECT id, nome, preco FROM " . Database::table('produtos') . " ORDER BY nome");
            $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $userId = Auth::user()?->id;
            $stmt = $this->db->prepare(
                "SELECT id, titulo, linha1, linha2, cidade, estado, cep FROM " . Database::table('enderecos') . " WHERE user_id = :user_id"
            );
            $stmt->execute(['user_id' => $userId]);
            $enderecos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->logDebug('PedidosController@create', ['user_id' => $userId, 'produtos' => count($produtos), 'enderecos' => count($enderecos)]);

            $this->layout('layouts/painel', 'cliente/pedidos/novo', [
                'produtos' => $produtos,
                'enderecos' => $enderecos,
            ]);
        } catch (\Throwable $e) {
            $this->logError('PedidosController@create erro', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    public function store(): void
    {
        $this->validateCsrf();
        $userId = Auth::user()?->id;

        $endereco_id = (int)($_POST['endereco_id'] ?? 0);
        $itens = $_POST['itens'] ?? [];

        if (!$endereco_id || empty($itens)) {
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

        $this->logDebug('PedidosController@store calculo', ['subtotal' => $subtotal, 'frete' => $frete, 'total' => $total, 'itens' => $itens]);

        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO " . Database::table('pedidos') . "
                (user_id, endereco_id, pagamento_id, status, subtotal, frete, total, created_at)
                VALUES (:user_id, :endereco_id, NULL, :status, :subtotal, :frete, :total, :created_at)"
            );
            $stmt->execute([
                'user_id' => $userId,
                'endereco_id' => $endereco_id,
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
            $this->logDebug('PedidosController@store criado', ['pedido_id' => $pedido_id, 'user_id' => $userId, 'total' => $total]);
        } catch (\Throwable $e) {
            $this->db->rollBack();
            $this->logError('PedidosController@store erro', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }

        $this->redirect('/cliente/pedidos?ok=1');
    }

    public function show($id): void
    {
        $id = (int)$id;
        $userId = Auth::user()?->id;

        $stmt = $this->db->prepare(
            "SELECT p.*, e.titulo AS endereco_titulo, e.linha1, e.linha2, e.cidade, e.estado, e.cep
             FROM " . Database::table('pedidos') . " p
             LEFT JOIN " . Database::table('enderecos') . " e ON e.id = p.endereco_id
             WHERE p.id = :id AND p.user_id = :user_id"
        );
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pedido) {
            http_response_code(404);
            exit('Pedido não encontrado');
        }

        $this->logDebug('PedidosController@show', ['pedido_id' => $id, 'user_id' => $userId]);

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

    public function edit($id): void
    {
        $id = (int)$id;
        $userId = Auth::user()?->id;

        try {
            $stmt = $this->db->prepare(
                "SELECT p.* FROM " . Database::table('pedidos') . " p WHERE p.id = :id AND p.user_id = :user_id"
            );
            $stmt->execute(['id' => $id, 'user_id' => $userId]);
            $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pedido) {
                http_response_code(404);
                exit('Pedido não encontrado');
            }

            $stmtItens = $this->db->prepare(
                "SELECT produto_id, nome_snapshot, qtd FROM " . Database::table('pedido_itens') . " WHERE pedido_id = :pedido_id"
            );
            $stmtItens->execute(['pedido_id' => $id]);
            $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

            $stmtProd = $this->db->query("SELECT id, nome, preco FROM " . Database::table('produtos') . " ORDER BY nome");
            $produtos = $stmtProd->fetchAll(PDO::FETCH_ASSOC);

            $stmtEnd = $this->db->prepare(
                "SELECT id, titulo, linha1, linha2, cidade, estado, cep FROM " . Database::table('enderecos') . " WHERE user_id = :user_id"
            );
            $stmtEnd->execute(['user_id' => $userId]);
            $enderecos = $stmtEnd->fetchAll(PDO::FETCH_ASSOC);

            $this->logDebug('PedidosController@edit', ['pedido_id' => $id, 'itens' => count($itens)]);

            $this->layout('layouts/painel', 'cliente/pedidos/editar', [
                'pedido' => $pedido,
                'itens' => $itens,
                'produtos' => $produtos,
                'enderecos' => $enderecos,
            ]);
        } catch (\Throwable $e) {
            $this->logError('PedidosController@edit erro', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    public function update($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;
        $userId = Auth::user()?->id;

        $endereco_id = (int)($_POST['endereco_id'] ?? 0);
        $itens = $_POST['itens'] ?? [];

        if (!$endereco_id || empty($itens)) {
            http_response_code(400);
            exit('Dados inválidos para atualizar pedido');
        }

        $subtotal = 0;
        foreach ($itens as $item) {
            $produto_id = (int)($item['produto_id'] ?? 0);
            $qtd = (int)($item['qtd'] ?? 0);
            if ($produto_id > 0 && $qtd > 0) {
                $stmt = $this->db->prepare("SELECT preco, nome FROM " . Database::table('produtos') . " WHERE id = :id");
                $stmt->execute(['id' => $produto_id]);
                $produto = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($produto) {
                    $subtotal += $produto['preco'] * $qtd;
                }
            }
        }

        $frete = 15.00;
        $total = $subtotal + $frete;
        $this->logDebug('PedidosController@update calculo', ['subtotal' => $subtotal, 'total' => $total, 'pedido_id' => $id]);

        $this->db->beginTransaction();
        try {
            $stmtPedido = $this->db->prepare(
                "UPDATE " . Database::table('pedidos') . "
                 SET endereco_id = :endereco_id, subtotal = :subtotal, frete = :frete, total = :total
                 WHERE id = :id AND user_id = :user_id"
            );
            $stmtPedido->execute([
                'endereco_id' => $endereco_id,
                'subtotal' => $subtotal,
                'frete' => $frete,
                'total' => $total,
                'id' => $id,
                'user_id' => $userId,
            ]);

            $del = $this->db->prepare("DELETE FROM " . Database::table('pedido_itens') . " WHERE pedido_id = :pedido_id");
            $del->execute(['pedido_id' => $id]);

            foreach ($itens as $item) {
                $produto_id = (int)($item['produto_id'] ?? 0);
                $qtd = (int)($item['qtd'] ?? 0);
                if ($produto_id > 0 && $qtd > 0) {
                    $stmtP = $this->db->prepare("SELECT nome, preco FROM " . Database::table('produtos') . " WHERE id = :id");
                    $stmtP->execute(['id' => $produto_id]);
                    $produto = $stmtP->fetch(PDO::FETCH_ASSOC);
                    if ($produto) {
                        $stmtItem = $this->db->prepare(
                            "INSERT INTO " . Database::table('pedido_itens') . "
                            (pedido_id, produto_id, nome_snapshot, qtd, preco_unitario, total_linha)
                            VALUES (:pedido_id, :produto_id, :nome, :qtd, :preco, :total)"
                        );
                        $stmtItem->execute([
                            'pedido_id' => $id,
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
            $this->logDebug('PedidosController@update ok', ['pedido_id' => $id]);
        } catch (\Throwable $e) {
            $this->db->rollBack();
            $this->logError('PedidosController@update erro', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }

        $this->redirect('/cliente/pedidos/' . $id);
    }

    public function destroy($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;
        $userId = Auth::user()?->id;

        $this->db->beginTransaction();
        try {
            $delItens = $this->db->prepare("DELETE FROM " . Database::table('pedido_itens') . " WHERE pedido_id = :pedido_id");
            $delItens->execute(['pedido_id' => $id]);

            $delPedido = $this->db->prepare("DELETE FROM " . Database::table('pedidos') . " WHERE id = :id AND user_id = :user_id");
            $delPedido->execute(['id' => $id, 'user_id' => $userId]);

            $this->db->commit();
            $this->logDebug('PedidosController@destroy', ['pedido_id' => $id]);
        } catch (\Throwable $e) {
            $this->db->rollBack();
            $this->logError('PedidosController@destroy erro', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }

        $this->redirect('/cliente/pedidos');
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
