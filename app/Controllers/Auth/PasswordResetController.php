<?php

namespace App\Controllers\Auth;

use App\Core\Controller;
use App\Core\Security;
use App\Repositories\UserRepository;
use App\Repositories\PasswordResetTokenRepository;

class PasswordResetController extends Controller
{
    private UserRepository $userRepo;
    private PasswordResetTokenRepository $tokenRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->tokenRepo = new PasswordResetTokenRepository();
    }

    public function esqueci(): void
    {
        $this->view('auth/esqueci-senha', []);
    }

    public function enviarToken(): void
    {
        $this->validateCsrf();

        $email = $_POST['email'] ?? '';

        if (empty($email)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Email é obrigatório.'];
            $this->redirect('/esqueci-senha');
        }

        $user = $this->userRepo->findByEmail($email);

        if (!$user) {
            $_SESSION['flash'] = ['type' => 'info', 'message' => 'Se o email existir, você receberá um link de recuperação.'];
            $this->redirect('/esqueci-senha');
        }

        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', time() + 3600);

        $this->tokenRepo->create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        $resetLink = "http://{$_SERVER['HTTP_HOST']}/redefinir-senha?token={$token}";

        $this->sendPasswordResetEmail($user->email, $user->nome, $resetLink);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Se o email existir, você receberá um link de recuperação.'];
        $this->redirect('/entrar');
    }

    public function redefinir(): void
    {
        $token = $_GET['token'] ?? '';

        if (empty($token) || !$this->tokenRepo->isTokenValid($token)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Link de recuperação inválido ou expirado.'];
            $this->redirect('/entrar');
            return;
        }

        $this->view('auth/redefinir-senha', ['token' => $token]);
    }

    public function atualizarSenha(): void
    {
        $this->validateCsrf();

        $token = $_POST['token'] ?? '';
        $novaSenha = $_POST['nova_senha'] ?? '';
        $confirmaSenha = $_POST['confirma_senha'] ?? '';

        if (empty($token) || !$this->tokenRepo->isTokenValid($token)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Link de recuperação inválido ou expirado.'];
            $this->redirect('/entrar');
        }

        if (empty($novaSenha) || empty($confirmaSenha)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Todos os campos são obrigatórios.'];
            $this->redirect("/redefinir-senha?token={$token}");
        }

        if ($novaSenha !== $confirmaSenha) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'As senhas não conferem.'];
            $this->redirect("/redefinir-senha?token={$token}");
        }

        if (strlen($novaSenha) < 6) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'A senha deve ter no mínimo 6 caracteres.'];
            $this->redirect("/redefinir-senha?token={$token}");
        }

        $resetToken = $this->tokenRepo->findByToken($token);

        if (!$resetToken) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Link de recuperação inválido.'];
            $this->redirect('/entrar');
        }

        $user = $this->userRepo->find($resetToken->user_id);

        if (!$user) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Usuário não encontrado.'];
            $this->redirect('/entrar');
        }

        $senhaHash = password_hash($novaSenha, PASSWORD_BCRYPT);
        $this->userRepo->update($user->id, ['senha' => $senhaHash]);

        $this->tokenRepo->deleteByToken($token);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Senha atualizada com sucesso! Faça login com sua nova senha.'];
        $this->redirect('/entrar');
    }

    private function sendPasswordResetEmail(string $email, string $nome, string $resetLink): void
    {
        $subject = 'Recuperação de Senha - FerramentasAi';
        $message = "
            <h2>Olá, {$nome}!</h2>
            <p>Você solicitou a recuperação de sua senha.</p>
            <p>Clique no link abaixo para redefinir sua senha:</p>
            <p><a href='{$resetLink}'>{$resetLink}</a></p>
            <p>Este link expira em 1 hora.</p>
            <p>Se você não solicitou isso, ignore este email.</p>
        ";

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";

        mail($email, $subject, $message, $headers);
    }
}
