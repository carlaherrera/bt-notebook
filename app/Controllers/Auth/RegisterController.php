<?php

namespace App\Controllers\Auth;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Flash;
use App\Core\Security;
use App\Core\Validator;
use App\Repositories\UserRepository;

class RegisterController extends Controller
{
    private UserRepository $users;

    public function __construct()
    {
        $this->users = new UserRepository();
    }

    public function index(): void
    {
        $this->view('auth/criar-conta');
    }

    public function registrar(): void
    {
        $this->validateCsrf();

        $nome = Security::sanitizeString($_POST['nome'] ?? '');
        $sobrenome = Security::sanitizeString($_POST['sobrenome'] ?? '');
        $email = Security::sanitizeString($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $confirmar_senha = $_POST['confirmar_senha'] ?? '';

        // Validações
        if (!Validator::required($nome) || !Validator::required($email) || !Validator::required($senha)) {
            Flash::set('erro', 'Todos os campos são obrigatórios.');
            $this->redirect('/criar-conta');
        }

        if (!Validator::email($email)) {
            Flash::set('erro', 'Email inválido.');
            $this->redirect('/criar-conta');
        }

        if (strlen($senha) < 6) {
            Flash::set('erro', 'A senha deve ter no mínimo 6 caracteres.');
            $this->redirect('/criar-conta');
        }

        if ($senha !== $confirmar_senha) {
            Flash::set('erro', 'As senhas não coincidem.');
            $this->redirect('/criar-conta');
        }

        // Verifica se email já existe
        $existingUser = $this->users->findByEmail($email);
        if ($existingUser) {
            Flash::set('erro', 'Este email já está registrado.');
            $this->redirect('/criar-conta');
        }

        // Cria novo usuário com role 'cliente' por padrão
        $userId = $this->users->insert([
            'nome' => $nome,
            'sobrenome' => $sobrenome,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_BCRYPT),
            'role' => 'cliente',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        if ($userId) {
            Flash::set('sucesso', 'Conta criada com sucesso! Faça login para continuar.');
            $this->redirect('/entrar');
        } else {
            Flash::set('erro', 'Erro ao criar conta. Tente novamente.');
            $this->redirect('/criar-conta');
        }
    }
}
