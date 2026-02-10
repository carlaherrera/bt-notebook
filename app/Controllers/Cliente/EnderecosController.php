<?php

namespace App\Controllers\Cliente;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Security;
use PDO;

class EnderecosController extends Controller
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
            "SELECT * FROM " . Database::table('enderecos') . " WHERE user_id = :user_id ORDER BY principal DESC, id DESC"
        );
        $stmt->execute(['user_id' => $userId]);
        $enderecos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->layout('layouts/painel', 'cliente/enderecos/index', [
            'enderecos' => $enderecos,
        ]);
    }

    public function create(): void
    {
        $this->layout('layouts/painel', 'cliente/enderecos/novo', []);
    }

    public function store(): void
    {
        $this->validateCsrf();
        $userId = Auth::user()?->id;

        $titulo = Security::sanitizeString($_POST['titulo'] ?? '');
        $linha1 = Security::sanitizeString($_POST['linha1'] ?? '');
        $linha2 = Security::sanitizeString($_POST['linha2'] ?? '');
        $cidade = Security::sanitizeString($_POST['cidade'] ?? '');
        $estado = Security::sanitizeString($_POST['estado'] ?? '');
        $cep = Security::sanitizeString($_POST['cep'] ?? '');
        $principal = isset($_POST['principal']) ? 1 : 0;

        if (!$titulo || !$linha1 || !$cidade || !$estado || !$cep) {
            http_response_code(400);
            exit('Dados obrigatórios faltando');
        }

        $stmt = $this->db->prepare(
            "INSERT INTO " . Database::table('enderecos') . "
            (user_id, titulo, linha1, linha2, cidade, estado, cep, principal, created_at)
            VALUES (:user_id, :titulo, :linha1, :linha2, :cidade, :estado, :cep, :principal, :created_at)"
        );
        $stmt->execute([
            'user_id' => $userId,
            'titulo' => $titulo,
            'linha1' => $linha1,
            'linha2' => $linha2,
            'cidade' => $cidade,
            'estado' => $estado,
            'cep' => $cep,
            'principal' => $principal,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->redirect('/cliente/enderecos?ok=1');
    }

    public function edit($id): void
    {
        $id = (int)$id;
        $userId = Auth::user()?->id;

        $stmt = $this->db->prepare(
            "SELECT * FROM " . Database::table('enderecos') . " WHERE id = :id AND user_id = :user_id"
        );
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        $endereco = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$endereco) {
            http_response_code(404);
            exit('Endereço não encontrado');
        }

        $this->layout('layouts/painel', 'cliente/enderecos/editar', ['endereco' => $endereco]);
    }

    public function update($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;
        $userId = Auth::user()?->id;

        $titulo = Security::sanitizeString($_POST['titulo'] ?? '');
        $linha1 = Security::sanitizeString($_POST['linha1'] ?? '');
        $linha2 = Security::sanitizeString($_POST['linha2'] ?? '');
        $cidade = Security::sanitizeString($_POST['cidade'] ?? '');
        $estado = Security::sanitizeString($_POST['estado'] ?? '');
        $cep = Security::sanitizeString($_POST['cep'] ?? '');
        $principal = isset($_POST['principal']) ? 1 : 0;

        if (!$titulo || !$linha1 || !$cidade || !$estado || !$cep) {
            http_response_code(400);
            exit('Dados obrigatórios faltando');
        }

        $stmt = $this->db->prepare(
            "UPDATE " . Database::table('enderecos') . "
             SET titulo = :titulo, linha1 = :linha1, linha2 = :linha2, cidade = :cidade,
                 estado = :estado, cep = :cep, principal = :principal, updated_at = :updated_at
             WHERE id = :id AND user_id = :user_id"
        );
        $stmt->execute([
            'titulo' => $titulo,
            'linha1' => $linha1,
            'linha2' => $linha2,
            'cidade' => $cidade,
            'estado' => $estado,
            'cep' => $cep,
            'principal' => $principal,
            'updated_at' => date('Y-m-d H:i:s'),
            'id' => $id,
            'user_id' => $userId,
        ]);

        $this->redirect('/cliente/enderecos?ok=1');
    }

    public function destroy($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;
        $userId = Auth::user()?->id;

        $stmt = $this->db->prepare(
            "DELETE FROM " . Database::table('enderecos') . " WHERE id = :id AND user_id = :user_id"
        );
        $stmt->execute(['id' => $id, 'user_id' => $userId]);

        $this->redirect('/cliente/enderecos?ok=1');
    }
}
