<?php
// /database/seed_suportes_notas.php
// Popula suportes (tickets + mensagens) e notas fiscais para o cliente padrão sem apagar demais dados.

declare(strict_types=1);

// Evita iniciar sessão e exibir HTML em execução CLI
define('SKIP_SESSION', true);
define('CLI_MODE', true);

require __DIR__ . '/../bootstrap.php';

use App\Core\Database;

$pdo = Database::getConnection();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

error_reporting(E_ALL);
ini_set('display_errors', '1');
set_exception_handler(function ($e) {
    fwrite(STDERR, "Erro: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n");
    exit(1);
});
$clienteEmail = 'cliente@exemplo.com.br';

$tables = [
    'usuarios' => Database::table('usuarios'),
    'tickets' => Database::table('tickets'),
    'ticket_mensagens' => Database::table('ticket_mensagens'),
    'notas_fiscais' => Database::table('notas_fiscais'),
    'pedidos' => Database::table('pedidos'),
];

$insert = function (string $table, array $row) use ($pdo) {
    $cols = array_keys($row);
    $placeholders = array_map(fn($c) => ':' . $c, $cols);
    $sql = 'INSERT INTO ' . $table . ' (' . implode(',', $cols) . ') VALUES (' . implode(',', $placeholders) . ')';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($row);
    return (int)$pdo->lastInsertId();
};

// Usuário cliente
$stmt = $pdo->prepare('SELECT id FROM ' . $tables['usuarios'] . ' WHERE email = :email LIMIT 1');
$stmt->execute(['email' => $clienteEmail]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$cliente) {
    echo "Usuário cliente não encontrado ({$clienteEmail}).\n";
    exit(1);
}
$clienteId = (int)$cliente['id'];

// Ticket (suporte) - limpa somente do cliente
$pdo->prepare('DELETE FROM ' . $tables['ticket_mensagens'] . ' WHERE ticket_id IN (SELECT id FROM ' . $tables['tickets'] . ' WHERE user_id = :uid)')->execute(['uid' => $clienteId]);
$pdo->prepare('DELETE FROM ' . $tables['tickets'] . ' WHERE user_id = :uid')->execute(['uid' => $clienteId]);

// Pega um pedido do cliente (para linkar nota e ticket)
$stmtPedido = $pdo->prepare('SELECT id FROM ' . $tables['pedidos'] . ' WHERE user_id = :uid ORDER BY id DESC LIMIT 1');
$stmtPedido->execute(['uid' => $clienteId]);
$pedidoRow = $stmtPedido->fetch();
$pedidoId = $pedidoRow['id'] ?? null;

// Se não houver pedido, cria endereço e pedido simples para vincular notas
if ($pedidoId === null) {
    $stmtEnd = $pdo->prepare('SELECT id FROM ' . Database::table('enderecos') . ' WHERE user_id = :uid LIMIT 1');
    $stmtEnd->execute(['uid' => $clienteId]);
    $endRow = $stmtEnd->fetch();
    $enderecoId = $endRow['id'] ?? null;
    if ($enderecoId === null) {
        $stmtInsEnd = $pdo->prepare('INSERT INTO ' . Database::table('enderecos') . ' (user_id, titulo, linha1, linha2, cidade, estado, cep, principal, created_at) VALUES (:user_id, :titulo, :linha1, :linha2, :cidade, :estado, :cep, :principal, :created_at)');
        $stmtInsEnd->execute([
            'user_id' => $clienteId,
            'titulo' => 'Endereço Padrão',
            'linha1' => 'Rua Exemplo, 123',
            'linha2' => 'Apto 1',
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'cep' => '01000-000',
            'principal' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $enderecoId = (int)$pdo->lastInsertId();
    }

    $stmtInsPedido = $pdo->prepare('INSERT INTO ' . $tables['pedidos'] . ' (user_id, endereco_id, pagamento_id, status, subtotal, frete, total, created_at) VALUES (:user_id, :endereco_id, NULL, :status, :subtotal, :frete, :total, :created_at)');
    $subtotalDummy = 150.00;
    $freteDummy = 15.00;
    $stmtInsPedido->execute([
        'user_id' => $clienteId,
        'endereco_id' => $enderecoId,
        'status' => 'criado',
        'subtotal' => $subtotalDummy,
        'frete' => $freteDummy,
        'total' => $subtotalDummy + $freteDummy,
        'created_at' => date('Y-m-d H:i:s'),
    ]);
    $pedidoId = (int)$pdo->lastInsertId();

    $stmtItem = $pdo->prepare('INSERT INTO ' . Database::table('pedido_itens') . ' (pedido_id, produto_id, nome_snapshot, sku_snapshot, qtd, preco_unitario, total_linha) VALUES (:pedido_id, NULL, :nome, :sku, :qtd, :preco, :total)');
    $stmtItem->execute([
        'pedido_id' => $pedidoId,
        'nome' => 'Item Demonstrativo',
        'sku' => 'DEMO-001',
        'qtd' => 1,
        'preco' => $subtotalDummy,
        'total' => $subtotalDummy,
    ]);
}

$ticketsSeed = [
    [
        'user_id' => $clienteId,
        'pedido_id' => $pedidoId,
        'assunto' => 'Dúvida sobre entrega',
        'categoria' => 'pedido',
        'status' => 'aberto',
        'prioridade' => 'media',
        'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
    ],
    [
        'user_id' => $clienteId,
        'pedido_id' => $pedidoId,
        'assunto' => 'Nota fiscal não recebida',
        'categoria' => 'fiscal',
        'status' => 'aberto',
        'prioridade' => 'baixa',
        'created_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
    ],
];

$idsTickets = [];
foreach ($ticketsSeed as $t) {
    $idsTickets[] = $insert($tables['tickets'], $t);
}

$mensagensSeed = [
    [
        'ticket_id' => $idsTickets[0] ?? 0,
        'autor_id' => $clienteId,
        'mensagem' => 'Quando meu pedido chega? Não recebi código de rastreio.',
        'anexos_json' => null,
        'created_at' => date('Y-m-d H:i:s', strtotime('-2 days +1 hour')),
    ],
    [
        'ticket_id' => $idsTickets[0] ?? 0,
        'autor_id' => $clienteId,
        'mensagem' => 'Seu pedido está em rota, previsão hoje até 18h.',
        'anexos_json' => null,
        'created_at' => date('Y-m-d H:i:s', strtotime('-2 days +2 hours')),
    ],
    [
        'ticket_id' => $idsTickets[1] ?? 0,
        'autor_id' => $clienteId,
        'mensagem' => 'Poderia reenviar a NF do último pedido?',
        'anexos_json' => null,
        'created_at' => date('Y-m-d H:i:s', strtotime('-1 days +1 hour')),
    ],
    [
        'ticket_id' => $idsTickets[1] ?? 0,
        'autor_id' => $clienteId,
        'mensagem' => 'NF reemitida e enviada para seu e-mail.',
        'anexos_json' => null,
        'created_at' => date('Y-m-d H:i:s', strtotime('-1 days +2 hours')),
    ],
];

foreach ($mensagensSeed as $m) {
    if (empty($m['ticket_id'])) continue;
    $insert($tables['ticket_mensagens'], $m);
}

echo "Tickets criados: " . count($idsTickets) . "\n";

// Notas fiscais (limpa somente do cliente)
$pdo->prepare('DELETE FROM ' . $tables['notas_fiscais'] . ' WHERE pedido_id IN (SELECT id FROM ' . $tables['pedidos'] . ' WHERE user_id = :uid)')->execute(['uid' => $clienteId]);

if ($pedidoId === null) {
    echo "Nenhum pedido encontrado para criar notas fiscais. Pulei notas.\n";
    exit(0);
}

$notasSeed = [
    [
        'pedido_id' => $pedidoId,
        'numero' => 'NF-1001',
        'serie' => '1',
        'valor' => 263.40,
        'data_emissao' => date('Y-m-d', strtotime('-3 days')),
        'link_download' => '/uploads/notas/nf-1001.pdf',
    ],
    [
        'pedido_id' => $pedidoId,
        'numero' => 'NF-1002',
        'serie' => '1',
        'valor' => 484.70,
        'data_emissao' => date('Y-m-d', strtotime('-7 days')),
        'link_download' => '/uploads/notas/nf-1002.pdf',
    ],
];

foreach ($notasSeed as $n) {
    $insert($tables['notas_fiscais'], $n);
}

echo "Notas criadas: " . count($notasSeed) . "\n";
