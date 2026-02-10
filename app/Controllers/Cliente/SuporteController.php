<?php

namespace App\Controllers\Cliente;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Security;
use PDO;

class SuporteController extends Controller
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
            "SELECT * FROM " . Database::table('tickets') . " WHERE user_id = :user_id ORDER BY created_at DESC"
        );
        $stmt->execute(['user_id' => $userId]);
        $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->layout('layouts/painel', 'cliente/suporte/index', [
            'tickets' => $tickets,
        ]);
    }

    public function create(): void
    {
        $categorias = ['Entrega', 'Pagamento', 'Troca/Devolução', 'Outros'];
        $this->layout('layouts/painel', 'cliente/suporte/novo', [
            'categorias' => $categorias,
        ]);
    }

    public function store(): void
    {
        $this->validateCsrf();
        $userId = Auth::user()?->id;

        $assunto = Security::sanitizeString($_POST['assunto'] ?? '');
        $categoria = Security::sanitizeString($_POST['categoria'] ?? '');
        $mensagem = Security::sanitizeString($_POST['mensagem'] ?? '');
        $pedido_id = !empty($_POST['pedido_id']) ? (int)($_POST['pedido_id'] ?? 0) : null;

        if (!$assunto || !$categoria || !$mensagem) {
            http_response_code(400);
            exit('Dados obrigatórios faltando');
        }

        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO " . Database::table('tickets') . "
                (user_id, pedido_id, assunto, categoria, status, prioridade, created_at)
                VALUES (:user_id, :pedido_id, :assunto, :categoria, :status, :prioridade, :created_at)"
            );
            $stmt->execute([
                'user_id' => $userId,
                'pedido_id' => $pedido_id,
                'assunto' => $assunto,
                'categoria' => $categoria,
                'status' => 'aberto',
                'prioridade' => 'media',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $ticket_id = (int)$this->db->lastInsertId();

            $stmt = $this->db->prepare(
                "INSERT INTO " . Database::table('ticket_mensagens') . "
                (ticket_id, autor_id, mensagem, created_at)
                VALUES (:ticket_id, :autor_id, :mensagem, :created_at)"
            );
            $stmt->execute([
                'ticket_id' => $ticket_id,
                'autor_id' => $userId,
                'mensagem' => $mensagem,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }

        $this->redirect('/cliente/suporte?ok=1');
    }

    public function show($id): void
    {
        $id = (int)$id;
        $userId = Auth::user()?->id;

        $stmt = $this->db->prepare(
            "SELECT * FROM " . Database::table('tickets') . " WHERE id = :id AND user_id = :user_id"
        );
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$ticket) {
            http_response_code(404);
            exit('Ticket não encontrado');
        }

        $stmt = $this->db->prepare(
            "SELECT * FROM " . Database::table('ticket_mensagens') . " WHERE ticket_id = :ticket_id ORDER BY created_at ASC"
        );
        $stmt->execute(['ticket_id' => $id]);
        $mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->layout('layouts/painel', 'cliente/suporte/show', [
            'ticket' => $ticket,
            'mensagens' => $mensagens,
        ]);
    }

    public function responder($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;
        $userId = Auth::user()?->id;

        $mensagem = Security::sanitizeString($_POST['mensagem'] ?? '');

        if (!$mensagem) {
            http_response_code(400);
            exit('Mensagem obrigatória');
        }

        $stmt = $this->db->prepare(
            "SELECT id FROM " . Database::table('tickets') . " WHERE id = :id AND user_id = :user_id"
        );
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            exit('Ticket não encontrado');
        }

        $stmt = $this->db->prepare(
            "INSERT INTO " . Database::table('ticket_mensagens') . "
            (ticket_id, autor_id, mensagem, created_at)
            VALUES (:ticket_id, :autor_id, :mensagem, :created_at)"
        );
        $stmt->execute([
            'ticket_id' => $id,
            'autor_id' => $userId,
            'mensagem' => $mensagem,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->redirect('/cliente/suporte/' . $id . '?ok=1');
    }

    public function edit($id): void
    {
        $id = (int)$id;
        $userId = Auth::user()?->id;

        $stmt = $this->db->prepare(
            "SELECT * FROM " . Database::table('tickets') . " WHERE id = :id AND user_id = :user_id"
        );
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$ticket) {
            http_response_code(404);
            exit('Ticket não encontrado');
        }

        $categorias = ['pedido', 'fiscal', 'suporte', 'outros'];
        $prioridades = ['baixa', 'media', 'alta'];
        $statusList = ['aberto', 'em_atendimento', 'resolvido', 'fechado'];

        $this->layout('layouts/painel', 'cliente/suporte/editar', [
            'ticket' => $ticket,
            'categorias' => $categorias,
            'prioridades' => $prioridades,
            'statusList' => $statusList,
        ]);
    }

    public function update($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;
        $userId = Auth::user()?->id;

        $assunto = Security::sanitizeString($_POST['assunto'] ?? '');
        $categoria = strtolower(Security::sanitizeString($_POST['categoria'] ?? ''));
        $prioridade = strtolower(Security::sanitizeString($_POST['prioridade'] ?? 'media'));
        $status = strtolower(Security::sanitizeString($_POST['status'] ?? 'aberto'));

        if (!$assunto || !$categoria || !$prioridade || !$status) {
            http_response_code(400);
            exit('Dados obrigatórios faltando');
        }

        $stmt = $this->db->prepare(
            "UPDATE " . Database::table('tickets') . "
             SET assunto = :assunto, categoria = :categoria, prioridade = :prioridade, status = :status
             WHERE id = :id AND user_id = :user_id"
        );
        $stmt->execute([
            'assunto' => $assunto,
            'categoria' => $categoria,
            'prioridade' => $prioridade,
            'status' => $status,
            'id' => $id,
            'user_id' => $userId,
        ]);

        $this->redirect('/cliente/suporte/' . $id . '?ok=1');
    }

    public function fechar($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;
        $userId = Auth::user()?->id;

        $stmt = $this->db->prepare(
            "UPDATE " . Database::table('tickets') . " SET status = :status WHERE id = :id AND user_id = :user_id"
        );
        $stmt->execute(['status' => 'fechado', 'id' => $id, 'user_id' => $userId]);

        $this->redirect('/cliente/suporte?ok=1');
    }
}
