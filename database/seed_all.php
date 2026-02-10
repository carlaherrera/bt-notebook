<?php
// /database/seed_all.php
// Popula tabelas principais com dados de demonstração

declare(strict_types=1);

require __DIR__ . '/../bootstrap.php';

use App\Core\Database;
use App\Repositories\UserRepository;

$pdo = Database::getConnection();
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

$tables = [
    'usuarios' => Database::table('usuarios'),
    'settings' => Database::table('settings'),
    'user_preferences' => Database::table('user_preferences'),
    'attachments' => Database::table('attachments'),
    'produtos' => Database::table('produtos'),
    'enderecos' => Database::table('enderecos'),
    'pedidos' => Database::table('pedidos'),
    'pedido_itens' => Database::table('pedido_itens'),
    'pedido_eventos' => Database::table('pedido_eventos'),
    'parceiros' => Database::table('parceiros'),
    'consignado_produtos' => Database::table('consignado_produtos'),
    'consignado_movimentacoes' => Database::table('consignado_movimentacoes'),
    'movimentacoes' => Database::table('movimentacoes'),
    'auditorias' => Database::table('auditorias'),
    'auditoria_itens' => Database::table('auditoria_itens'),
    'auditoria_historico' => Database::table('auditoria_historico'),
    'relatorios_log' => Database::table('relatorios_log'),
];

// Helpers
$insert = function (string $table, array $row) use ($pdo, $driver) {
    $cols = array_keys($row);
    $placeholders = array_map(fn($c) => ':' . $c, $cols);
    $sql = 'INSERT INTO ' . $table . ' (' . implode(',', $cols) . ') VALUES (' . implode(',', $placeholders) . ')';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($row);
    return $driver === 'mysql' ? (int)$pdo->lastInsertId() : (int)$pdo->lastInsertId();
};

// 1) Usuários
// Limpa tabelas dependentes antes de recriar usuários
$pdo->exec('DELETE FROM ' . $tables['pedido_eventos']);
$pdo->exec('DELETE FROM ' . $tables['pedido_itens']);
$pdo->exec('DELETE FROM ' . $tables['pedidos']);
$pdo->exec('DELETE FROM ' . $tables['enderecos']);
$pdo->exec('DELETE FROM ' . $tables['relatorios_log']);
$pdo->exec('DELETE FROM ' . $tables['user_preferences']);
$pdo->exec('DELETE FROM ' . $tables['parceiros']);
$pdo->exec('DELETE FROM ' . $tables['consignado_produtos']);
$pdo->exec('DELETE FROM ' . $tables['consignado_movimentacoes']);
$pdo->exec('DELETE FROM ' . $tables['movimentacoes']);
$pdo->exec('DELETE FROM ' . $tables['auditoria_historico']);
$pdo->exec('DELETE FROM ' . $tables['auditoria_itens']);
$pdo->exec('DELETE FROM ' . $tables['auditorias']);
$pdo->exec('DELETE FROM ' . $tables['usuarios']);

$repo = new UserRepository();
$senhaHash = password_hash('dev123', PASSWORD_DEFAULT);
$usuarios = [
    ['nome' => 'Admin', 'sobrenome' => 'Master', 'email' => 'admin@exemplo.com.br', 'senha' => $senhaHash, 'role' => 'admin', 'status' => 1, 'whatsapp' => '(11) 90000-0001', 'imagem_perfil' => null],
    ['nome' => 'Colaborador', 'sobrenome' => 'Silva', 'email' => 'colaborador@exemplo.com.br', 'senha' => $senhaHash, 'role' => 'colaborador', 'status' => 1, 'whatsapp' => '(11) 90000-0002', 'imagem_perfil' => null],
    ['nome' => 'Cliente', 'sobrenome' => 'Souza', 'email' => 'cliente@exemplo.com.br', 'senha' => $senhaHash, 'role' => 'cliente', 'status' => 1, 'whatsapp' => '(11) 90000-0003', 'imagem_perfil' => null],
    ['nome' => 'João', 'sobrenome' => 'Lima', 'email' => 'joao.parceiro@exemplo.com.br', 'senha' => $senhaHash, 'role' => 'parceiro', 'status' => 1, 'whatsapp' => '(11) 90000-1111', 'imagem_perfil' => null],
    ['nome' => 'Marina', 'sobrenome' => 'Costa', 'email' => 'marina.parceiro@exemplo.com.br', 'senha' => $senhaHash, 'role' => 'parceiro', 'status' => 1, 'whatsapp' => '(19) 90000-2222', 'imagem_perfil' => null],
];
$idsUsuarios = [];
foreach ($usuarios as $u) {
    $idsUsuarios[$u['email']] = $repo->insert($u);
}

echo "Usuários criados: " . implode(', ', array_keys($idsUsuarios)) . "\n";

// 2) Settings
$pdo->exec('DELETE FROM ' . $tables['settings']);
$insert($tables['settings'], [
    'org_name' => 'FerramentasAi',
    'cep' => '01001-000',
    'rua' => 'Av. Paulista',
    'numero' => '1000',
    'cidade' => 'São Paulo',
    'estado' => 'SP',
    'telefone' => '(11) 4000-1000',
    'whatsapp' => '(11) 90000-1234',
    'email' => 'contato@ferramentas.ai',
    'cnpj' => '12.345.678/0001-99',
    'logo_light_path' => '/uploads/fallback-images/logo-light-fallback.svg',
    'logo_dark_path' => '/uploads/fallback-images/logo-dark-fallback.svg',
    'favicon_path' => '/uploads/fallback-images/favicon-fallback.svg',
    'primary_color' => '#2563eb',
    'secondary_color' => '#db0000',
    'accent_color' => '#9333ea',
    'slogan' => 'Plataforma de gestão inteligente',
]);

echo "Settings criado.\n";

// 3) User preferences (tema)
$pdo->exec('DELETE FROM ' . $tables['user_preferences']);
$insert($tables['user_preferences'], [
    'user_id' => $idsUsuarios['admin@exemplo.com.br'],
    'theme_preference' => 'system'
]);
$insert($tables['user_preferences'], [
    'user_id' => $idsUsuarios['colaborador@exemplo.com.br'],
    'theme_preference' => 'dark'
]);
$insert($tables['user_preferences'], [
    'user_id' => $idsUsuarios['cliente@exemplo.com.br'],
    'theme_preference' => 'light'
]);

echo "User preferences criadas.\n";

// 4) Parceiros
$pdo->exec('DELETE FROM ' . $tables['parceiros']);
$parceiros = [
    ['nome' => 'Academia Alpha', 'tipo' => 'academia', 'documento' => '12.345.678/0001-10', 'cidade' => 'São Paulo', 'contato' => 'João Lima', 'telefone' => '(11) 90000-1111', 'email' => 'contato@alpha.com', 'status' => 'ativo', 'ticket_medio' => 220.50, 'atualizado_em' => '2026-01-25 10:00:00', 'usuario_id' => $idsUsuarios['joao.parceiro@exemplo.com.br'] ?? null],
    ['nome' => 'Personal Beta', 'tipo' => 'personal', 'documento' => '98.765.432/0001-20', 'cidade' => 'Campinas', 'contato' => 'Marina Costa', 'telefone' => '(19) 90000-2222', 'email' => 'marina@beta.com', 'status' => 'ativo', 'ticket_medio' => 180.00, 'atualizado_em' => '2026-01-26 09:30:00', 'usuario_id' => $idsUsuarios['marina.parceiro@exemplo.com.br'] ?? null],
];
$idsParceiros = [];
foreach ($parceiros as $p) {
    $idsParceiros[] = $insert($tables['parceiros'], $p);
}

echo "Parceiros criados: " . count($idsParceiros) . "\n";

// 4b) Produtos
$pdo->exec('DELETE FROM ' . $tables['produtos']);
$produtosSeed = [
    ['nome' => 'Whey Isolado 900g', 'sku' => 'WHEY-ISO-900', 'categoria' => 'Proteína', 'preco' => 189.90, 'estoque_loja' => 120, 'estoque_consignado' => 68, 'minimo' => 40, 'status' => 'ativo'],
    ['nome' => 'Creatina 300g', 'sku' => 'CREA-300', 'categoria' => 'Performance', 'preco' => 89.90, 'estoque_loja' => 80, 'estoque_consignado' => 55, 'minimo' => 30, 'status' => 'ativo'],
    ['nome' => 'Pré-treino Nitro', 'sku' => 'PRE-NITRO', 'categoria' => 'Energia', 'preco' => 129.90, 'estoque_loja' => 22, 'estoque_consignado' => 18, 'minimo' => 20, 'status' => 'ativo'],
    ['nome' => 'BCAA 4:1:1', 'sku' => 'BCAA-411', 'categoria' => 'Aminoácidos', 'preco' => 69.90, 'estoque_loja' => 14, 'estoque_consignado' => 9, 'minimo' => 18, 'status' => 'alerta'],
    ['nome' => 'Glutamina 300g', 'sku' => 'GLUT-300', 'categoria' => 'Recuperação', 'preco' => 79.90, 'estoque_loja' => 9, 'estoque_consignado' => 6, 'minimo' => 15, 'status' => 'critico'],
    ['nome' => 'Barra Proteica 45g', 'sku' => 'BARRA-45', 'categoria' => 'Snacks', 'preco' => 12.90, 'estoque_loja' => 260, 'estoque_consignado' => 130, 'minimo' => 80, 'status' => 'ativo'],
];
$_idsProdutos = [];
foreach ($produtosSeed as $prod) {
    $_idsProdutos[$prod['sku']] = $insert($tables['produtos'], $prod);
}

echo "Produtos criados: " . count($produtosSeed) . "\n";

// 4c) Endereços do cliente
$pdo->exec('DELETE FROM ' . $tables['enderecos']);
$enderecosSeed = [
    [
        'user_id' => $idsUsuarios['cliente@exemplo.com.br'],
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
        'user_id' => $idsUsuarios['cliente@exemplo.com.br'],
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

echo "Endereços criados: " . count($idsEnderecos) . "\n";

// 4d) Pedidos do cliente (sem método de pagamento)
$pdo->exec('DELETE FROM ' . $tables['pedido_eventos']);
$pdo->exec('DELETE FROM ' . $tables['pedido_itens']);
$pdo->exec('DELETE FROM ' . $tables['pedidos']);

$pedidoSeed = [
    [
        'user_id' => $idsUsuarios['cliente@exemplo.com.br'],
        'endereco_id' => $idsEnderecos[0] ?? null,
        'pagamento_id' => null,
        'status' => 'criado',
        'subtotal' => 0,
        'frete' => 15.00,
        'total' => 0,
        'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
    ],
];

$idsPedidos = [];
foreach ($pedidoSeed as $p) {
    $idsPedidos[] = $insert($tables['pedidos'], $p);
}

// Itens para o primeiro pedido
$pdo->exec('DELETE FROM ' . $tables['pedido_itens']);
if (!empty($idsPedidos)) {
    $pedidoId = $idsPedidos[0];
    $itens = [
        ['sku' => 'WHEY-ISO-900', 'qtd' => 2],
        ['sku' => 'CREA-300', 'qtd' => 1],
    ];
    $subtotal = 0;
    foreach ($itens as $it) {
        $sku = $it['sku'];
        $prodId = $_idsProdutos[$sku] ?? null;
        if (!$prodId) { continue; }
        $stmt = $pdo->prepare('SELECT nome, preco FROM ' . $tables['produtos'] . ' WHERE id = :id');
        $stmt->execute(['id' => $prodId]);
        $prod = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($prod) {
            $linhaTotal = $prod['preco'] * $it['qtd'];
            $subtotal += $linhaTotal;
            $insert($tables['pedido_itens'], [
                'pedido_id' => $pedidoId,
                'produto_id' => $prodId,
                'nome_snapshot' => $prod['nome'],
                'sku_snapshot' => $sku,
                'qtd' => $it['qtd'],
                'preco_unitario' => $prod['preco'],
                'total_linha' => $linhaTotal,
            ]);
        }
    }

    $total = $subtotal + 15.00;
    $upd = $pdo->prepare('UPDATE ' . $tables['pedidos'] . ' SET subtotal = :subtotal, total = :total WHERE id = :id');
    $upd->execute(['subtotal' => $subtotal, 'total' => $total, 'id' => $pedidoId]);
    echo "Pedidos criados: " . count($idsPedidos) . " (subtotal R$ {$subtotal})\n";
}

// 5) Consignado produtos
$pdo->exec('DELETE FROM ' . $tables['consignado_produtos']);
$produtos = [
    ['parceiro_id' => $idsParceiros[0], 'produto' => 'Creatina 300g', 'sku' => 'CR-300', 'categoria' => 'Suplemento', 'lote' => 'L2301', 'nf' => 'NF-1001', 'estoque' => 30, 'minimo' => 10, 'vendido_mes' => 12, 'devolucao' => 1, 'prazo_dev' => '30 dias'],
    ['parceiro_id' => $idsParceiros[0], 'produto' => 'Whey 900g', 'sku' => 'WH-900', 'categoria' => 'Suplemento', 'lote' => 'L2302', 'nf' => 'NF-1002', 'estoque' => 18, 'minimo' => 8, 'vendido_mes' => 9, 'devolucao' => 0, 'prazo_dev' => '45 dias'],
    ['parceiro_id' => $idsParceiros[1], 'produto' => 'Thermo Burner', 'sku' => 'TB-150', 'categoria' => 'Suplemento', 'lote' => 'L2303', 'nf' => 'NF-2001', 'estoque' => 22, 'minimo' => 6, 'vendido_mes' => 14, 'devolucao' => 2, 'prazo_dev' => '30 dias'],
];
foreach ($produtos as $prod) {
    $insert($tables['consignado_produtos'], $prod);
}

echo "Produtos consignados criados: " . count($produtos) . "\n";

// 6) Consignado movimentações
$pdo->exec('DELETE FROM ' . $tables['consignado_movimentacoes']);
$movsConsig = [
    ['parceiro_id' => $idsParceiros[0], 'tipo' => 'transferencia', 'descricao' => 'Envio inicial', 'produto' => 'Creatina 300g', 'sku' => 'CR-300', 'quantidade' => 20, 'valor' => 1200.00, 'usuario' => 'Admin', 'data' => '2026-01-20 10:00:00'],
    ['parceiro_id' => $idsParceiros[0], 'tipo' => 'venda_parceiro', 'descricao' => 'Vendas semana 4', 'produto' => 'Whey 900g', 'sku' => 'WH-900', 'quantidade' => 8, 'valor' => 960.00, 'usuario' => 'Colaborador', 'data' => '2026-01-27 18:00:00'],
    ['parceiro_id' => $idsParceiros[1], 'tipo' => 'devolucao', 'descricao' => 'Ajuste de estoque', 'produto' => 'Thermo Burner', 'sku' => 'TB-150', 'quantidade' => 3, 'valor' => 180.00, 'usuario' => 'Admin', 'data' => '2026-01-28 09:15:00'],
];
foreach ($movsConsig as $mc) {
    $insert($tables['consignado_movimentacoes'], $mc);
}

echo "Movimentações consignado criadas: " . count($movsConsig) . "\n";

// 7) Movimentações gerais
$pdo->exec('DELETE FROM ' . $tables['movimentacoes']);
$movsGerais = [
    ['tipo' => 'entrada', 'parceiro_id' => null, 'produto' => 'BCAA 120caps', 'quantidade' => 50, 'nf_ref' => 'NF-3001', 'lote' => 'L3001', 'datahora' => '2026-01-19 14:00:00', 'observacao' => 'Compra fornecedor'],
    ['tipo' => 'transferencia', 'parceiro_id' => $idsParceiros[0], 'produto' => 'Creatina 300g', 'quantidade' => 20, 'nf_ref' => 'NF-3100', 'lote' => 'L2301', 'datahora' => '2026-01-20 10:00:00', 'observacao' => 'Envio parceiro'],
    ['tipo' => 'venda', 'parceiro_id' => $idsParceiros[1], 'produto' => 'Thermo Burner', 'quantidade' => 5, 'nf_ref' => 'NF-3200', 'lote' => 'L2303', 'datahora' => '2026-01-27 18:00:00', 'observacao' => 'Vendas semana'],
    ['tipo' => 'devolucao', 'parceiro_id' => $idsParceiros[1], 'produto' => 'Thermo Burner', 'quantidade' => 3, 'nf_ref' => 'NF-3300', 'lote' => 'L2303', 'datahora' => '2026-01-28 09:15:00', 'observacao' => 'Devolução parcial'],
];
foreach ($movsGerais as $mg) {
    $insert($tables['movimentacoes'], $mg);
}

echo "Movimentações gerais criadas: " . count($movsGerais) . "\n";

// 8) Auditorias
$pdo->exec('DELETE FROM ' . $tables['auditorias']);
$auditoriaId = $insert($tables['auditorias'], [
    'status' => 'pendente',
    'descricao' => 'Conferência mensal estoque parceiros'
]);

$pdo->exec('DELETE FROM ' . $tables['auditoria_itens']);
$itensAud = [
    ['auditoria_id' => $auditoriaId, 'produto' => 'Creatina 300g', 'local' => 'Academia Alpha', 'qtde_sistema' => 30, 'qtde_fisica' => 28, 'status' => 'divergencia'],
    ['auditoria_id' => $auditoriaId, 'produto' => 'Whey 900g', 'local' => 'Academia Alpha', 'qtde_sistema' => 18, 'qtde_fisica' => 18, 'status' => 'ok'],
];
foreach ($itensAud as $ia) {
    $insert($tables['auditoria_itens'], $ia);
}

$pdo->exec('DELETE FROM ' . $tables['auditoria_historico']);
$histAud = [
    ['auditoria_id' => $auditoriaId, 'acao' => 'abertura', 'descricao' => 'Início da contagem', 'usuario' => 'Admin', 'data' => '2026-01-28 08:00:00'],
    ['auditoria_id' => $auditoriaId, 'acao' => 'ajuste', 'descricao' => 'Baixa de 2 unidades Creatina', 'usuario' => 'Admin', 'data' => '2026-01-28 10:00:00'],
];
foreach ($histAud as $ha) {
    $insert($tables['auditoria_historico'], $ha);
}

echo "Auditoria e histórico criados.\n";

// 9) Relatórios log
$pdo->exec('DELETE FROM ' . $tables['relatorios_log']);
$relLogs = [
    ['usuario_id' => $idsUsuarios['admin@exemplo.com.br'], 'nome' => 'Relatório parceiros', 'formato' => 'pdf', 'filtros' => json_encode(['periodo' => '2026-01', 'parceiro' => 'todos']), 'gerado_em' => '2026-01-27 12:00:00'],
    ['usuario_id' => $idsUsuarios['colaborador@exemplo.com.br'], 'nome' => 'Relatório consignado', 'formato' => 'excel', 'filtros' => json_encode(['periodo' => '2026-01', 'parceiro' => 'Academia Alpha']), 'gerado_em' => '2026-01-28 09:00:00'],
];
foreach ($relLogs as $rl) {
    $insert($tables['relatorios_log'], $rl);
}

echo "Relatórios log criados: " . count($relLogs) . "\n";

// 10) Attachments (ex.: anexos recentes)
$pdo->exec('DELETE FROM ' . $tables['attachments']);
$atts = [
    ['path' => '/uploads/docs/contrato-alpha.pdf', 'filename' => 'contrato-alpha.pdf', 'mime_type' => 'application/pdf', 'size' => 210000],
    ['path' => '/uploads/docs/nf-transferencia-3100.pdf', 'filename' => 'nf-transferencia-3100.pdf', 'mime_type' => 'application/pdf', 'size' => 95000],
];
foreach ($atts as $att) {
    $insert($tables['attachments'], $att);
}

echo "Attachments criados: " . count($atts) . "\n";

echo "Seed concluído com sucesso.\n";
