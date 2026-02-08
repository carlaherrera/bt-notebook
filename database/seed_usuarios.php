<?php
// /database/seed_usuarios.php
// Popula a tabela usuarios com dados iniciais

declare(strict_types=1);

// Carrega autoload, DB e config
require __DIR__ . '/../bootstrap.php';

use App\Repositories\UserRepository;

// Instancia o repositório de usuários
$repo = new UserRepository();

// Senha padrão para todos
$senhaHash = password_hash('dev123', PASSWORD_DEFAULT);

// Lista de usuários iniciais
$usuarios = [
    [
        'nome'  => 'Admin',
        'email' => 'admin@exemplo.com.br',
        'senha' => $senhaHash,
        'role'  => 'admin',
        'status'=> 1,
    ],
    [
        'nome'  => 'Colaborador',
        'email' => 'colaborador@exemplo.com.br',
        'senha' => $senhaHash,
        'role'  => 'colaborador',
        'status'=> 1,
    ],
    [
        'nome'  => 'Cliente',
        'email' => 'cliente@exemplo.com.br',
        'senha' => $senhaHash,
        'role'  => 'cliente',
        'status'=> 1,
    ],
];

// Faz o insert de cada usuário
foreach ($usuarios as $user) {
    $repo->insert($user);
    echo "Usuário criado: {$user['nome']} ({$user['email']})\n";
}

echo "Seed finalizado com sucesso.\n";
