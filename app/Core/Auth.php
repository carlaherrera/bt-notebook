<?php

declare(strict_types=1);

namespace App\Core;

use App\Repositories\UserRepository;
use App\Repositories\RememberTokenRepository;

class Auth
{
    private const REMEMBER_COOKIE = 'remember_me';
    private const REMEMBER_DAYS = 30;

    /**
     * Retorna o usuário autenticado da sessão
     */
    public static function user(): ?object
    {
        if (isset($_SESSION['user'])) {
            return (object) $_SESSION['user'];
        }

        // Tentativa de lembrar usuário via cookie persistente
        $cookie = $_COOKIE[self::REMEMBER_COOKIE] ?? null;
        if (!$cookie) {
            return null;
        }

        $parts = explode(':', $cookie, 2);
        if (count($parts) !== 2) {
            self::clearRememberCookie();
            return null;
        }

        [$selector, $token] = $parts;
        if ($selector === '' || $token === '') {
            self::clearRememberCookie();
            return null;
        }

        $repo = new RememberTokenRepository();
        $record = $repo->findBySelector($selector);
        if (!$record) {
            self::clearRememberCookie();
            return null;
        }

        $expiresAt = strtotime($record['expires_at'] ?? 'now');
        if ($expiresAt < time()) {
            $repo->deleteBySelector($selector);
            self::clearRememberCookie();
            return null;
        }

        $valid = hash_equals($record['token_hash'], hash('sha256', $token));
        if (!$valid) {
            $repo->deleteBySelector($selector);
            self::clearRememberCookie();
            return null;
        }

        // Carrega usuário
        $users = new UserRepository();
        $user = $users->find((int) $record['user_id']);
        if (!$user) {
            $repo->deleteBySelector($selector);
            self::clearRememberCookie();
            return null;
        }

        // Recria sessão
        self::login([
            'id' => $user->id,
            'nome' => $user->nome,
            'sobrenome' => $user->sobrenome ?? null,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status,
            'imagem_perfil' => $user->imagem_perfil ?? null,
            'whatsapp' => $user->whatsapp ?? null,
        ]);

        return (object) $_SESSION['user'];
    }

    /**
     * Verifica se o usuário está autenticado
     */
    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Retorna o papel (role) do usuário autenticado
     */
    public static function role(): ?string
    {
        $user = self::user();
        return $user?->role ?? null;
    }

    /**
     * Verifica se o usuário tem um papel específico
     */
    public static function hasRole(string $role): bool
    {
        return self::role() === $role;
    }

    /**
     * Autentica um usuário
     */
    public static function login(array $userData): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
        $_SESSION['user'] = $userData;
    }

    /**
     * Faz logout do usuário
     */
    public static function logout(): void
    {
        self::forgetRememberMe();
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        unset($_SESSION['user']);
        session_destroy();
    }

    /**
     * Emite cookie "lembrar-me" persistente.
     */
    public static function remember(int $userId): void
    {
        $selector = bin2hex(random_bytes(8));
        $token = bin2hex(random_bytes(32));
        $hash = hash('sha256', $token);
        $expiresAt = date('Y-m-d H:i:s', time() + (self::REMEMBER_DAYS * 86400));

        $repo = new RememberTokenRepository();
        // Opcional: revoga tokens anteriores do usuário
        $repo->deleteByUser($userId);
        $repo->create($userId, $selector, $hash, $expiresAt);

        $cookieValue = $selector . ':' . $token;
        $params = session_get_cookie_params();
        setcookie(
            self::REMEMBER_COOKIE,
            $cookieValue,
            [
                'expires' => time() + (self::REMEMBER_DAYS * 86400),
                'path' => '/',
                'domain' => $params['domain'] ?? '',
                'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
                'httponly' => true,
                'samesite' => 'Lax',
            ]
        );
    }

    /**
     * Remove cookie e tokens persistentes.
     */
    public static function forgetRememberMe(): void
    {
        $cookie = $_COOKIE[self::REMEMBER_COOKIE] ?? null;
        if ($cookie) {
            $parts = explode(':', $cookie, 2);
            if (count($parts) === 2 && $parts[0] !== '') {
                $repo = new RememberTokenRepository();
                $repo->deleteBySelector($parts[0]);
            }
        }
        self::clearRememberCookie();
    }

    private static function clearRememberCookie(): void
    {
        setcookie(self::REMEMBER_COOKIE, '', [
            'expires' => time() - 3600,
            'path' => '/',
            'domain' => '',
            'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        unset($_COOKIE[self::REMEMBER_COOKIE]);
    }
}
