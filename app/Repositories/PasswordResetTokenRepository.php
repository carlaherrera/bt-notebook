<?php

namespace App\Repositories;

use App\Core\Repository;
use App\Core\Database;

class PasswordResetTokenRepository extends Repository
{
    protected string $table = 'password_reset_tokens';
    protected string $entityClass = \stdClass::class;

    public function findByToken(string $token): ?object
    {
        return $this->findWhere('token', $token);
    }

    public function findByUserId(int $userId): ?object
    {
        return $this->findWhere('user_id', $userId);
    }

    public function deleteByToken(string $token): bool
    {
        $table = Database::table('password_reset_tokens');
        $sql = "DELETE FROM " . $table . " WHERE token = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$token]);
    }

    public function deleteExpiredTokens(): int
    {
        $table = Database::table('password_reset_tokens');
        $sql = "DELETE FROM " . $table . " WHERE expires_at < NOW()";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function isTokenValid(string $token): bool
    {
        $table = Database::table('password_reset_tokens');
        $sql = "SELECT 1 FROM " . $table . " WHERE token = ? AND expires_at > NOW() LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$token]);
        return $stmt->rowCount() > 0;
    }
}
