<?php
// /database/seed_pedidos_enderecos.php
// Popula endereços e pedidos (com itens) para o cliente padrão, sem apagar demais dados.
// Pré-requisito: produtos e usuário cliente já existentes.

declare(strict_types=1);

require __DIR__ . '/../bootstrap.php';

use App\Core\Database;

$pdo = Database::getConnection();
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
$prefix = Database::tablePrefix();

// Configurações
$clienteEmail = 'cliente@exemplo.com.br';
$freteFixo = 15.00;

$tables = [
    'usuarios' => Database::table('usuarios'),
    'enderecos' => Database::table('enderecos'),
    'pedidos' => Database::table('pedidos'),
    'pedido_itens' => Database::table('pedido_itens'),
    'pedido_eventos' => Database::table('pedido_eventos'),
    'produtos' => Database::table('produtos'),
];

$insert = function (string $table, array $row) use ($pdo) {
    $cols = array_keys($row);
    $placeholders = array_map(fn($c) => ':' . $c, $cols);
    $sql = 'INSERT INTO ' . $table . ' (' . implode(',', $cols) . ') VALUES (' . implode(',', $placeholders) . ')';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($row);
    return (int)$pdo->lastInsertId();
};

// Busca usuário cliente
$stmt = $pdo->prepare('SELECT id FROM ' . $tables['usuarios'] . ' WHERE email = :email LIMIT 1');
$stmt->execute(['email' => $clienteEmail]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$cliente) {
    echo "Usuário cliente não encontrado ({$clienteEmail}).\n";
    exit(1);
}
$clienteId = (int)$cliente['id'];

// Endereços: limpa apenas do cliente para evitar duplicar
$delEnd = $pdo->prepare('DELETE FROM ' . $tables['enderecos'] . ' WHERE user_id = :uid');
$delEnd->execute(['uid' => $clienteId]);

$enderecosSeed = [
    [
        'user_id' => $clienteId,
        'titulo' => 'Casa',
        'linha1' => 'Rua das Laranjeiras, 123',
        'linha2' => 'Apto 45',
        'cidade' => 'São Paulo',
        'estado' => 'SP',
        'cep' => '01310-000',
        'principal' => 1,
        'created_at' => date('Y-m-d H:i:s'),
    ],
    [
        'user_id' => $clienteId,
        'titulo' => 'Trabalho',
        'linha1' => 'Av. Paulista, 1000',
        'linha2' => 'Conj. 1207',
        'cidade' => 'São Paulo',
        'estado' => 'SP',
        'cep' => '01310-100',
        'principal' => 0,
        'created_at' => date('Y-m-d H:i:s'),
    ],
];

$idsEnderecos = [];
foreach ($enderecosSeed as $e) {
    $idsEnderecos[] = $insert($tables['enderecos'], $e);
}

// Produtos por SKU
$skuToId = [];
$stmt = $pdo->query('SELECT id, sku, preco, nome FROM ' . $tables['produtos']);
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $p) {
    $skuToId[$p['sku']] = ['id' => (int)$p['id'], 'preco' => (float)$p['preco'], 'nome' => $p['nome']];
}

if (empty($skuToId)) {
    echo "Nenhum produto encontrado para criar itens.\n";
    exit(0);
}

// Pedidos: limpa somente do cliente
$pdo->prepare('DELETE FROM ' . $tables['pedido_eventos'] . ' WHERE pedido_id IN (SELECT id FROM ' . $tables['pedidos'] . ' WHERE user_id = :uid)')->execute(['uid' => $clienteId]);
$pdo->prepare('DELETE FROM ' . $tables['pedido_itens'] . ' WHERE pedido_id IN (SELECT id FROM ' . $tables['pedidos'] . ' WHERE user_id = :uid)')->execute(['uid' => $clienteId]);
$pdo->prepare('DELETE FROM ' . $tables['pedidos'] . ' WHERE user_id = :uid')->execute(['uid' => $clienteId]);

$pedidoBase = [
    'user_id' => $clienteId,
    'endereco_id' => $idsEnderecos[0] ?? null,
    'pagamento_id' => null,
    'status' => 'criado',
    'subtotal' => 0,
    'frete' => $freteFixo,
    'total' => 0,
    'created_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
];

$pedidoId = $insert($tables['pedidos'], $pedidoBase);

$itensSeed = [
    ['sku' => 'WHEY-ISO-900', 'qtd' => 2],
    ['sku' => 'CREA-300', 'qtd' => 1],
];

$subtotal = 0;
foreach ($itensSeed as $it) {
    $sku = $it['sku'];
    $qtd = (int)$it['qtd'];
    if ($qtd <= 0 || empty($skuToId[$sku])) continue;
    $prod = $skuToId[$sku];
    $linhaTotal = $prod['preco'] * $qtd;
    $subtotal += $linhaTotal;
    $insert($tables['pedido_itens'], [
        'pedido_id' => $pedidoId,
        'produto_id' => $prod['id'],
        'nome_snapshot' => $prod['nome'],
        'sku_snapshot' => $sku,
        'qtd' => $qtd,
        'preco_unitario' => $prod['preco'],
        'total_linha' => $linhaTotal,
    ]);
}

$total = $subtotal + $freteFixo;
$pdo->prepare('UPDATE ' . $tables['pedidos'] . ' SET subtotal = :s, total = :t WHERE id = :id')
    ->execute(['s' => $subtotal, 't' => $total, 'id' => $pedidoId]);

echo "Endereços inseridos: " . count($idsEnderecos) . "\n";
echo "Pedido criado ID {$pedidoId} subtotal R$ " . number_format($subtotal, 2, ',', '.') . " total R$ " . number_format($total, 2, ',', '.') . "\n";
