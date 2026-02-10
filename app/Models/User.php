<?php
// /app/Models/User.php
// Entidade User (dados e comportamento, sem acesso ao DB)

declare(strict_types=1);

namespace App\Models;

class User
{
    public ?int $id = null;
    public string $nome;
    public string $email;
    public string $role;
    public int $status;
    public string $senha;
    public ?string $sobrenome = null;
    public ?string $whatsapp = null;
    public ?string $imagem_perfil = null;
    public ?string $created_at = null;
    public ?string $deleted_at = null;

    /**
     * A entidade pode ser criada a partir de um array.
     */
    public static function fromArray(array $data): self
    {
        $user = new self();

        foreach ($data as $key => $value) {
            // Ignora índices numéricos e só aceita chaves da entidade
            if (is_string($key) && property_exists($user, $key)) {
                // Type casting para propriedades tipadas
                if ($key === 'id' && $value !== null) {
                    $user->{$key} = (int)$value;
                } elseif ($key === 'status' && $value !== null) {
                    $user->{$key} = (int)$value;
                } else {
                    $user->{$key} = $value;
                }
            }
        }

        return $user;
    }

}
