<?php
// /app/Core/Repository.php
// Classe base para Repositories (CRUD genérico)

declare(strict_types=1);

namespace App\Core;

use PDO;

abstract class Repository
{
    protected PDO $db;
    protected string $table;
    protected string $entityClass;
    protected bool $softDelete = false;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    protected function assertSafeIdentifier(string $identifier): void
    {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $identifier)) {
            throw new \InvalidArgumentException('Identificador inválido.');
        }
    }

    /**
     * Retorna um registro pelo ID
     */
    public function find(int $id): ?object
    {
        $this->assertSafeIdentifier($this->table);
        $table = Database::table($this->table);
        $this->assertSafeIdentifier($table);
        $where = "id = :id";
        if ($this->softDelete) {
            $where .= " AND deleted_at IS NULL";
        }
        $stmt = $this->db->prepare("SELECT * FROM {$table} WHERE {$where} LIMIT 1");
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    /**
     * Retorna todos os registros
     */
    public function findAll(): array
    {
        $this->assertSafeIdentifier($this->table);
        $table = Database::table($this->table);
        $this->assertSafeIdentifier($table);
        $sql = "SELECT * FROM {$table}";
        if ($this->softDelete) {
            $sql .= " WHERE deleted_at IS NULL";
        }
        $stmt = $this->db->query($sql);
        return $this->hydrateMultiple($stmt->fetchAll());
    }

    /**
     * Busca por campo
     */
    public function findWhere(string $field, mixed $value): ?object
    {
        $this->assertSafeIdentifier($this->table);
        $this->assertSafeIdentifier($field);
        $table = Database::table($this->table);
        $this->assertSafeIdentifier($table);
        $where = "{$field} = :value";
        if ($this->softDelete) {
            $where .= " AND deleted_at IS NULL";
        }
        $stmt = $this->db->prepare("SELECT * FROM {$table} WHERE {$where} LIMIT 1");
        $stmt->execute(['value' => $value]);

        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    /**
     * Inserção
     */
    public function insert(array $data): int
    {
        $this->assertSafeIdentifier($this->table);
        foreach (array_keys($data) as $col) {
            $this->assertSafeIdentifier((string) $col);
        }
        $table = Database::table($this->table);
        $this->assertSafeIdentifier($table);
        // monta o INSERT dinamicamente
        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ":".$col, $columns);

        $sql = "INSERT INTO {$table} ("
             . implode(',', $columns) . ") VALUES ("
             . implode(',', $placeholders) . ")";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Atualização
     */
    public function update(int $id, array $data): bool
    {
        $this->assertSafeIdentifier($this->table);
        foreach (array_keys($data) as $col) {
            $this->assertSafeIdentifier((string) $col);
        }
        $table = Database::table($this->table);
        $this->assertSafeIdentifier($table);
        $setClause = implode(', ', array_map(fn($col) => "{$col} = :{$col}", array_keys($data)));

        $sql = "UPDATE {$table} SET {$setClause} WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;

        return $stmt->execute($data);
    }

    /**
     * Exclusão
     */
    public function delete(int $id): bool
    {
        $this->assertSafeIdentifier($this->table);
        $table = Database::table($this->table);
        $this->assertSafeIdentifier($table);
        if ($this->softDelete) {
            $stmt = $this->db->prepare("UPDATE {$table} SET deleted_at = :ts WHERE id = :id");
            return $stmt->execute(['id' => $id, 'ts' => date('Y-m-d H:i:s')]);
        }

        $stmt = $this->db->prepare("DELETE FROM {$table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Restaura um registro soft-deleted (quando habilitado).
     */
    public function restore(int $id): bool
    {
        if (!$this->softDelete) {
            return false;
        }
        $this->assertSafeIdentifier($this->table);
        $table = Database::table($this->table);
        $this->assertSafeIdentifier($table);
        $stmt = $this->db->prepare("UPDATE {$table} SET deleted_at = NULL WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Transforma array do DB em objeto da entidade
     */
    protected function hydrate(array $row): object
    {
        return $this->entityClass::fromArray($row);
    }

    /**
     * Transforma vários rows em objetos
     */
    protected function hydrateMultiple(array $rows): array
    {
        return array_map(fn($row) => $this->hydrate($row), $rows);
    }
}
