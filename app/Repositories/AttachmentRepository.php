<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

class AttachmentRepository
{
    private PDO $pdo;
    private string $table;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->table = Database::table('attachments');
    }

    public function create(string $path, string $filename, string $mime, int $size): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (path, filename, mime_type, size) VALUES (:p, :f, :m, :s)");
        $stmt->execute([
            'p' => $path,
            'f' => $filename,
            'm' => $mime,
            's' => $size,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
