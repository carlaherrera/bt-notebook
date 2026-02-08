<?php

namespace App\Controllers\Cliente;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Flash;
use App\Core\Validator;
use App\Core\Upload;
use App\Repositories\UserRepository;

class PerfilController extends Controller
{
    private UserRepository $users;

    public function __construct()
    {
        $this->requireRole('cliente');
        $this->users = new UserRepository();
    }

    public function index(): void
    {
        $this->layout('layouts/painel', 'cliente/perfil/index');
    }

    public function atualizar(): void
    {
        $this->validateCsrf();
        $user = Auth::user();

        $nome    = trim($_POST['nome'] ?? '');
        $sobrenome = trim($_POST['sobrenome'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $whats   = trim($_POST['whatsapp'] ?? '');

        if (!Validator::required($nome) || !Validator::email($email) || !Validator::maxLength($nome, 120) || !Validator::maxLength($sobrenome, 120) || !Validator::maxLength($whats, 20)) {
            Flash::set('erro', 'Dados inválidos.');
            $this->redirect('/cliente/perfil');
        }

        $avatarPath = $user->imagem_perfil ?? null;
        try {
            $upload = Upload::uploadImage('imagem_perfil', 'public/uploads/avatars', $avatarPath);
            if ($upload) {
                $avatarPath = $upload;
            }
        } catch (\Throwable $e) {
            Flash::set('erro', 'Falha ao processar imagem: ' . $e->getMessage());
            $this->redirect('/cliente/perfil');
        }

        $this->users->updateProfile((int) $user->id, [
            'nome' => $nome,
            'sobrenome' => $sobrenome,
            'email' => $email,
            'whatsapp' => $whats,
            'imagem_perfil' => $avatarPath,
        ]);

        Auth::login([
            'id' => $user->id,
            'nome' => $nome,
            'sobrenome' => $sobrenome,
            'email' => $email,
            'role' => $user->role,
            'status' => $user->status,
            'imagem_perfil' => $avatarPath,
            'whatsapp' => $whats,
        ]);

        Flash::set('sucesso', 'Perfil atualizado.');
        $this->redirect('/cliente/perfil');
    }
}
