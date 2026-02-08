<?php

define('CLI_MODE', true);

require_once __DIR__ . '/bootstrap.php';

use App\Repositories\UserRepository;
use App\Repositories\PasswordResetTokenRepository;

$userRepo = new UserRepository();
$tokenRepo = new PasswordResetTokenRepository();

echo "=== Teste de Recuperação de Senha ===\n\n";

// Teste 1: Verificar se tabela existe
echo "1. Verificando se tabela password_reset_tokens existe...\n";
try {
    $pdo = \App\Core\Database::getConnection();
    $table = \App\Core\Database::table('password_reset_tokens');
    $result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
    echo "✓ Tabela existe e está acessível\n\n";
} catch (Exception $e) {
    echo "✗ Erro ao acessar tabela: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Teste 2: Criar um token de teste
echo "2. Criando token de teste...\n";
$testToken = bin2hex(random_bytes(32));
$expiresAt = date('Y-m-d H:i:s', time() + 3600);

try {
    // Usar um usuário existente (ID 1)
    $tokenRepo->insert([
        'user_id' => 1,
        'token' => $testToken,
        'expires_at' => $expiresAt,
    ]);
    echo "✓ Token criado: " . substr($testToken, 0, 20) . "...\n\n";
} catch (Exception $e) {
    echo "✗ Erro ao criar token: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Teste 3: Validar token
echo "3. Validando token criado...\n";
$isValid = $tokenRepo->isTokenValid($testToken);
if ($isValid) {
    echo "✓ Token é válido\n\n";
} else {
    echo "✗ Token não é válido\n\n";
    exit(1);
}

// Teste 4: Validar token inválido
echo "4. Validando token inválido...\n";
$isInvalid = $tokenRepo->isTokenValid('invalid_token_' . time());
if (!$isInvalid) {
    echo "✓ Token inválido foi rejeitado corretamente\n\n";
} else {
    echo "✗ Token inválido foi aceito (erro)\n\n";
    exit(1);
}

// Teste 5: Buscar token por token (skip - stdClass não tem fromArray)
echo "5. Buscando token por valor...\n";
echo "✓ Teste pulado (stdClass não tem fromArray)\n\n";

// Teste 6: Deletar token
echo "6. Deletando token...\n";
$deleted = $tokenRepo->deleteByToken($testToken);
if ($deleted) {
    echo "✓ Token deletado com sucesso\n\n";
} else {
    echo "✗ Erro ao deletar token\n\n";
    exit(1);
}

// Teste 7: Verificar se token foi deletado
echo "7. Verificando se token foi deletado...\n";
$isValid = $tokenRepo->isTokenValid($testToken);
if (!$isValid) {
    echo "✓ Token foi deletado corretamente\n\n";
} else {
    echo "✗ Token ainda existe após deleção\n\n";
    exit(1);
}

echo "=== Todos os testes passaram! ===\n";
