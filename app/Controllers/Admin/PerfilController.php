<?php

namespace App\Controllers\Admin;

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
        $this->requireRole('admin');
        $this->users = new UserRepository();
    }

    public function index(): void
    {
        $this->layout('layouts/painel', 'admin/perfil/index');
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
            $this->redirect('/admin/perfil');
        }

        $avatarPath = $user->imagem_perfil ?? null;
        $removeAvatar = isset($_POST['imagem_perfil_remove']) && $_POST['imagem_perfil_remove'] === '1';

        // Normaliza e remove imagem atual, se solicitado
        if ($removeAvatar && $avatarPath) {
            $absolute = $avatarPath;
            if (!str_starts_with($absolute, BASE_PATH)) {
                $absolute = BASE_PATH . '/public/' . ltrim($absolute, '/');
            }
            if (is_file($absolute)) {
                @unlink($absolute);
            }
            $avatarPath = null;
        }

        // Só tenta upload se um arquivo novo foi enviado
        $hasUpload = isset($_FILES['imagem_perfil']) && ($_FILES['imagem_perfil']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK;
        if ($hasUpload) {
            try {
                $upload = Upload::uploadImage('imagem_perfil', 'public/uploads/avatars', $user->imagem_perfil ?? null);
                if ($upload) {
                    $avatarPath = $upload;
                }
            } catch (\Throwable $e) {
                Flash::set('erro', 'Falha ao processar imagem: ' . $e->getMessage());
                $this->redirect('/admin/perfil');
            }
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
        $this->redirect('/admin/perfil');
    }
}
