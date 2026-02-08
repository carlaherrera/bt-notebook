<?php

declare(strict_types=1);

namespace App\Core;

class Security
{
    private const CSRF_KEY = '_csrf_token';
    private const RATE_LIMIT_KEY = 'global_rate_limit';

    public static function ensureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function csrfToken(): string
    {
        self::ensureSession();

        if (empty($_SESSION[self::CSRF_KEY])) {
            $_SESSION[self::CSRF_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::CSRF_KEY];
    }

    public static function validateCsrf(?string $token): bool
    {
        self::ensureSession();

        if (empty($_SESSION[self::CSRF_KEY])) {
            return false;
        }

        return hash_equals($_SESSION[self::CSRF_KEY], (string) $token);
    }

    /**
     * Controle simples de tentativas de login (throttle) em sessão.
     */
    private static function pruneLoginAttempts(string $key, int $decaySeconds): array
    {
        self::ensureSession();
        $now = time();
        $attempts = $_SESSION['login_throttle'][$key] ?? [];
        $attempts = array_values(array_filter($attempts, fn ($ts) => ($now - (int)$ts) < $decaySeconds));
        $_SESSION['login_throttle'][$key] = $attempts;
        return $attempts;
    }

    public static function canAttemptLogin(string $key, int $maxAttempts = 5, int $decaySeconds = 900): bool
    {
        $attempts = self::pruneLoginAttempts($key, $decaySeconds);
        return count($attempts) < $maxAttempts;
    }

    public static function addLoginFailure(string $key, int $decaySeconds = 900): void
    {
        $attempts = self::pruneLoginAttempts($key, $decaySeconds);
        $attempts[] = time();
        $_SESSION['login_throttle'][$key] = $attempts;
    }

    public static function clearLoginAttempts(string $key): void
    {
        self::ensureSession();
        unset($_SESSION['login_throttle'][$key]);
    }

    public static function loginWaitSeconds(string $key, int $maxAttempts = 5, int $decaySeconds = 900): int
    {
        $attempts = self::pruneLoginAttempts($key, $decaySeconds);
        if (count($attempts) < $maxAttempts) {
            return 0;
        }
        $now = time();
        $oldest = (int)min($attempts);
        $wait = $decaySeconds - ($now - $oldest);
        return $wait > 0 ? $wait : 0;
    }

    /**
     * Rate limit simples por IP para requisições genéricas (ex.: POST).
     */
    private static function pruneRateLimit(string $key, int $decaySeconds): array
    {
        self::ensureSession();
        $now = time();
        $hits = $_SESSION[self::RATE_LIMIT_KEY][$key] ?? [];
        $hits = array_values(array_filter($hits, fn ($ts) => ($now - (int)$ts) < $decaySeconds));
        $_SESSION[self::RATE_LIMIT_KEY][$key] = $hits;
        return $hits;
    }

    public static function rateLimitExceeded(string $key, int $maxHits, int $decaySeconds): bool
    {
        $hits = self::pruneRateLimit($key, $decaySeconds);
        return count($hits) >= $maxHits;
    }

    public static function addRateHit(string $key, int $decaySeconds): void
    {
        $hits = self::pruneRateLimit($key, $decaySeconds);
        $hits[] = time();
        $_SESSION[self::RATE_LIMIT_KEY][$key] = $hits;
    }

    public static function rateLimitWaitSeconds(string $key, int $maxHits, int $decaySeconds): int
    {
        $hits = self::pruneRateLimit($key, $decaySeconds);
        if (count($hits) < $maxHits) {
            return 0;
        }
        $now = time();
        $oldest = (int)min($hits);
        $wait = $decaySeconds - ($now - $oldest);
        return $wait > 0 ? $wait : 0;
    }

    public static function sanitizeString(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
