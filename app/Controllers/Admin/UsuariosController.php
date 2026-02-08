<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Security;
use App\Repositories\UserRepository;

class UsuariosController extends Controller
{
    private UserRepository $users;

    public function __construct()
    {
        $this->requireRole('admin');
        $this->users = new UserRepository();
    }

    public function index(): void
    {
        $usuarios = $this->users->findAll();
        $this->layout('layouts/painel', 'admin/usuarios/index', ['usuarios' => $usuarios]);
    }

    public function create(): void
    {
        $this->layout('layouts/painel', 'admin/usuarios/novo', []);
    }

    public function store(): void
    {
        $this->validateCsrf();

        $nome = Security::sanitizeString($_POST['nome'] ?? '');
        $sobrenome = Security::sanitizeString($_POST['sobrenome'] ?? '');
        $email = Security::sanitizeString($_POST['email'] ?? '');
        $role = Security::sanitizeString($_POST['role'] ?? 'cliente');
        $senha = $_POST['senha'] ?? '';
        $senhaConfirmacao = $_POST['senha_confirmacao'] ?? '';
        $ativo = isset($_POST['ativo']) ? 1 : 0;

        if (empty($nome) || empty($email) || empty($senha)) {
            http_response_code(400);
            exit('Nome, email e senha são obrigatórios.');
        }

        if ($senha !== $senhaConfirmacao) {
            http_response_code(400);
            exit('As senhas não coincidem.');
        }

        if (strlen($senha) < 8) {
            http_response_code(400);
            exit('A senha deve ter no mínimo 8 caracteres.');
        }

        $existente = $this->users->findByEmail($email);
        if ($existente) {
            http_response_code(400);
            exit('Este email já está registrado.');
        }

        $data = [
            'nome' => $nome,
            'sobrenome' => $sobrenome,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_BCRYPT),
            'role' => $role,
            'status' => $ativo,
        ];

        $this->users->insert($data);
        $this->redirect('/admin/usuarios');
    }

    public function show($id): void
    {
        $id = (int) $id;
        $usuario = $this->users->find($id);
        if (!$usuario) {
            http_response_code(404);
            exit('Usuário não encontrado');
        }
        $this->layout('layouts/painel', 'admin/usuarios/ver', ['usuario' => $usuario]);
    }

    public function edit($id): void
    {
        $id = (int) $id;
        $usuario = $this->users->find($id);
        if (!$usuario) {
            http_response_code(404);
            exit('Usuário não encontrado');
        }
        $this->layout('layouts/painel', 'admin/usuarios/editar', ['usuario' => $usuario]);
    }

    public function update($id): void
    {
        $this->validateCsrf();
        $id = (int) $id;
        $usuario = $this->users->find($id);
        if (!$usuario) {
            http_response_code(404);
            exit('Usuário não encontrado');
        }
        $data = [
            'nome' => Security::sanitizeString($_POST['nome'] ?? ''),
            'sobrenome' => Security::sanitizeString($_POST['sobrenome'] ?? ''),
            'email' => Security::sanitizeString($_POST['email'] ?? ''),
            'role' => Security::sanitizeString($_POST['role'] ?? ''),
            'status' => isset($_POST['ativo']) ? 1 : 0,
        ];
        $this->users->update($id, $data);
        $this->redirect('/admin/usuarios');
    }

    public function toggle($id): void
    {
        $this->validateCsrf();
        $id = (int) $id;
        $usuario = $this->users->find($id);
        if (!$usuario) {
            http_response_code(404);
            exit('Usuário não encontrado');
        }
        $novoStatus = (int)!((int)($usuario->status ?? 0));
        $this->users->update($id, ['status' => $novoStatus]);
        $this->redirect('/admin/usuarios');
    }
}
