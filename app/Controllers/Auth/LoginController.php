<?php

namespace App\Controllers\Auth;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Flash;
use App\Core\Security;
use App\Core\Validator;
use App\Repositories\UserRepository;

class LoginController extends Controller
{
    private UserRepository $users;

    public function __construct()
    {
        $this->users = new UserRepository();
    }

    public function index(): void
    {
        $this->view('auth/entrar');
    }

    public function autenticar(): void
    {
        $this->validateCsrf();

        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $remember = isset($_POST['remember']);

        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = hash('sha256', $ip . '|' . strtolower(trim($email)));
        $maxAttempts = 5;
        $decay = 15 * 60; // 15 minutos

        if (!Security::canAttemptLogin($key, $maxAttempts, $decay)) {
            $wait = Security::loginWaitSeconds($key, $maxAttempts, $decay);
            Flash::set('erro', 'Muitas tentativas. Aguarde ' . ceil($wait / 60) . ' minutos.');
            $this->debug("Login throttled para IP {$ip}, email {$email}, wait {$wait}s");
            $this->redirect('/entrar');
        }

        if (!Validator::email($email) || !Validator::required($senha)) {
            Security::addLoginFailure($key, $decay);
            Flash::set('erro', 'Credenciais inválidas.');
            $this->redirect('/entrar');
        }

        $user = $this->users->findByEmail($email);

        if (!$user || !password_verify($senha, $user->senha)) {
            Security::addLoginFailure($key, $decay);
            Flash::set('erro', 'Credenciais inválidas.');
            $this->redirect('/entrar');
        }

        Security::clearLoginAttempts($key);

        Auth::login([
            'id' => $user->id,
            'nome' => $user->nome,
            'sobrenome' => $user->sobrenome ?? null,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status,
            'imagem_perfil' => $user->imagem_perfil ?? null,
            'whatsapp' => $user->whatsapp ?? null,
        ]);

        if ($remember) {
            Auth::remember((int) $user->id);
        } else {
            Auth::forgetRememberMe();
        }

        $this->redirect($this->redirectByRole($user->role));
    }

    public function sair(): void
    {
        Auth::logout();
        $this->redirect('/entrar');
    }

    private function redirectByRole(string $role): string
    {
        return match ($role) {
            'admin' => '/admin',
            'colaborador' => '/colaborador',
            default => '/cliente',
        };
    }
}
