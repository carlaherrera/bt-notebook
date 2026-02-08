<?php
// /app/Repositories/UserRepository.php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Repository;
use App\Models\User;

class UserRepository extends Repository
{
    protected string $table = 'usuarios';
    protected string $entityClass = User::class;
    protected bool $softDelete = true;

    /**
     * Aqui você coloca queries específicas de usuário.
     * Ex: busca por email, troca de senha, filtros etc.
     */
    public function findByEmail(string $email): ?User
    {
        return $this->findWhere('email', $email);
    }

    public function updateProfile(int $id, array $data): bool
    {
        $allowed = ['nome', 'sobrenome', 'email', 'whatsapp', 'imagem_perfil'];
        $filtered = [];
        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $filtered[$field] = $data[$field];
            }
        }

        if (empty($filtered)) {
            return false;
        }

        return $this->update($id, $filtered);
    }
}
