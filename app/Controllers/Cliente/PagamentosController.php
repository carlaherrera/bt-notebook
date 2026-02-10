<?php

namespace App\Controllers\Cliente;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Security;
use PDO;

class PagamentosController extends Controller
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
            "SELECT * FROM " . Database::table('metodos_pagamento') . " WHERE user_id = :user_id ORDER BY principal DESC, id DESC"
        );
        $stmt->execute(['user_id' => $userId]);
        $metodos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->layout('layouts/painel', 'cliente/pagamentos/index', [
            'metodos' => $metodos,
        ]);
    }

    public function create(): void
    {
        $this->layout('layouts/painel', 'cliente/pagamentos/novo', []);
    }

    public function store(): void
    {
        $this->validateCsrf();
        $userId = Auth::user()?->id;

        $tipo = Security::sanitizeString($_POST['tipo'] ?? '');
        $apelido = Security::sanitizeString($_POST['apelido'] ?? '');
        $masked = Security::sanitizeString($_POST['masked'] ?? '');
        $validade = Security::sanitizeString($_POST['validade'] ?? '');
        $principal = isset($_POST['principal']) ? 1 : 0;

        if (!$tipo || !$apelido) {
            http_response_code(400);
            exit('Dados obrigatórios faltando');
        }

        $stmt = $this->db->prepare(
            "INSERT INTO " . Database::table('metodos_pagamento') . "
            (user_id, tipo, apelido, masked, validade, principal, created_at)
            VALUES (:user_id, :tipo, :apelido, :masked, :validade, :principal, :created_at)"
        );
        $stmt->execute([
            'user_id' => $userId,
            'tipo' => $tipo,
            'apelido' => $apelido,
            'masked' => $masked,
            'validade' => $validade,
            'principal' => $principal,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->redirect('/cliente/pagamentos?ok=1');
    }

    public function edit($id): void
    {
        $id = (int)$id;
        $userId = Auth::user()?->id;

        $stmt = $this->db->prepare(
            "SELECT * FROM " . Database::table('metodos_pagamento') . " WHERE id = :id AND user_id = :user_id"
        );
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        $metodo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$metodo) {
            http_response_code(404);
            exit('Método de pagamento não encontrado');
        }

        $this->layout('layouts/painel', 'cliente/pagamentos/editar', ['metodo' => $metodo]);
    }

    public function update($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;
        $userId = Auth::user()?->id;

        $apelido = Security::sanitizeString($_POST['apelido'] ?? '');
        $validade = Security::sanitizeString($_POST['validade'] ?? '');
        $principal = isset($_POST['principal']) ? 1 : 0;

        if (!$apelido) {
            http_response_code(400);
            exit('Dados obrigatórios faltando');
        }

        $stmt = $this->db->prepare(
            "UPDATE " . Database::table('metodos_pagamento') . "
             SET apelido = :apelido, validade = :validade, principal = :principal
             WHERE id = :id AND user_id = :user_id"
        );
        $stmt->execute([
            'apelido' => $apelido,
            'validade' => $validade,
            'principal' => $principal,
            'id' => $id,
            'user_id' => $userId,
        ]);

        $this->redirect('/cliente/pagamentos?ok=1');
    }

    public function destroy($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;
        $userId = Auth::user()?->id;

        $stmt = $this->db->prepare(
            "DELETE FROM " . Database::table('metodos_pagamento') . " WHERE id = :id AND user_id = :user_id"
        );
        $stmt->execute(['id' => $id, 'user_id' => $userId]);

        $this->redirect('/cliente/pagamentos?ok=1');
    }
}
