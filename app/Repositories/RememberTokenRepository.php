<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

class RememberTokenRepository
{
    private PDO $pdo;
    private string $table;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->table = Database::table('remember_tokens');
    }

    public function create(int $userId, string $selector, string $tokenHash, string $expiresAt): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO {$this->table} (user_id, selector, token_hash, expires_at) VALUES (:u, :s, :t, :e)"
        );
        $stmt->execute([
            'u' => $userId,
            's' => $selector,
            't' => $tokenHash,
            'e' => $expiresAt,
        ]);
    }

    public function findBySelector(string $selector): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE selector = :s LIMIT 1");
        $stmt->execute(['s' => $selector]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function deleteBySelector(string $selector): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE selector = :s");
        $stmt->execute(['s' => $selector]);
    }

    public function deleteByUser(int $userId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE user_id = :u");
        $stmt->execute(['u' => $userId]);
    }
}
