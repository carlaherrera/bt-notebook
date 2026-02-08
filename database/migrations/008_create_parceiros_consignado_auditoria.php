<?php
// /database/migrations/008_create_parceiros_consignado_auditoria.php
// Cria tabelas de parceiros, consignado, movimentações e auditoria

declare(strict_types=1);

require_once dirname(__DIR__, 1) . '/../bootstrap.php';

use App\Core\Database;

$pdo = Database::getConnection();
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
$prefix = Database::tablePrefix();

$tables = [
    'parceiros' => $prefix . 'parceiros',
    'consig_produtos' => $prefix . 'consignado_produtos',
    'consig_movs' => $prefix . 'consignado_movimentacoes',
    'movimentacoes' => $prefix . 'movimentacoes',
    'auditorias' => $prefix . 'auditorias',
    'auditoria_itens' => $prefix . 'auditoria_itens',
    'auditoria_historico' => $prefix . 'auditoria_historico',
    'relatorios_log' => $prefix . 'relatorios_log',
    'usuarios' => $prefix . 'usuarios',
];

if ($driver === 'mysql') {
    $sqlStatements = [
        // Parceiros
        "CREATE TABLE IF NOT EXISTS {$tables['parceiros']} (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            tipo VARCHAR(50) NOT NULL DEFAULT 'academia',
            documento VARCHAR(50) NULL,
            cidade VARCHAR(255) NULL,
            contato VARCHAR(255) NULL,
            telefone VARCHAR(50) NULL,
            email VARCHAR(255) NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'ativo',
            ticket_medio DECIMAL(10,2) NULL,
            atualizado_em DATETIME NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
        // Consignado produtos
        "CREATE TABLE IF NOT EXISTS {$tables['consig_produtos']} (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            parceiro_id INT UNSIGNED NOT NULL,
            produto VARCHAR(255) NOT NULL,
            sku VARCHAR(100) NULL,
            categoria VARCHAR(100) NULL,
            lote VARCHAR(100) NULL,
            nf VARCHAR(100) NULL,
            estoque INT NOT NULL DEFAULT 0,
            minimo INT NOT NULL DEFAULT 0,
            vendido_mes INT NOT NULL DEFAULT 0,
            devolucao INT NOT NULL DEFAULT 0,
            prazo_dev VARCHAR(100) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_consig_prod_parceiro FOREIGN KEY (parceiro_id) REFERENCES {$tables['parceiros']}(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
        // Consignado movimentações
        "CREATE TABLE IF NOT EXISTS {$tables['consig_movs']} (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            parceiro_id INT UNSIGNED NOT NULL,
            tipo VARCHAR(30) NOT NULL,
            descricao TEXT NULL,
            produto VARCHAR(255) NOT NULL,
            sku VARCHAR(100) NULL,
            quantidade INT NOT NULL DEFAULT 0,
            valor DECIMAL(10,2) NULL,
            usuario VARCHAR(255) NULL,
            data DATETIME NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_consig_mov_parceiro (parceiro_id),
            INDEX idx_consig_mov_data (data),
            CONSTRAINT fk_consig_mov_parceiro FOREIGN KEY (parceiro_id) REFERENCES {$tables['parceiros']}(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
        // Movimentações gerais
        "CREATE TABLE IF NOT EXISTS {$tables['movimentacoes']} (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            tipo VARCHAR(30) NOT NULL,
            parceiro_id INT UNSIGNED NULL,
            produto VARCHAR(255) NOT NULL,
            quantidade INT NOT NULL DEFAULT 0,
            nf_ref VARCHAR(100) NULL,
            lote VARCHAR(100) NULL,
            datahora DATETIME NOT NULL,
            observacao TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_mov_data (datahora),
            CONSTRAINT fk_mov_parceiro FOREIGN KEY (parceiro_id) REFERENCES {$tables['parceiros']}(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
        // Auditorias
        "CREATE TABLE IF NOT EXISTS {$tables['auditorias']} (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            status VARCHAR(30) NOT NULL DEFAULT 'pendente',
            descricao TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
        // Auditoria itens
        "CREATE TABLE IF NOT EXISTS {$tables['auditoria_itens']} (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            auditoria_id INT UNSIGNED NOT NULL,
            produto VARCHAR(255) NOT NULL,
            local VARCHAR(255) NULL,
            qtde_sistema INT NOT NULL DEFAULT 0,
            qtde_fisica INT NULL,
            status VARCHAR(30) NOT NULL DEFAULT 'pendente',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_aud_item_auditoria FOREIGN KEY (auditoria_id) REFERENCES {$tables['auditorias']}(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
        // Auditoria histórico
        "CREATE TABLE IF NOT EXISTS {$tables['auditoria_historico']} (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            auditoria_id INT UNSIGNED NOT NULL,
            acao VARCHAR(255) NOT NULL,
            descricao TEXT NULL,
            usuario VARCHAR(255) NULL,
            data DATETIME NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_aud_hist_auditoria FOREIGN KEY (auditoria_id) REFERENCES {$tables['auditorias']}(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
        // Relatórios gerados
        "CREATE TABLE IF NOT EXISTS {$tables['relatorios_log']} (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            usuario_id INT UNSIGNED NULL,
            nome VARCHAR(255) NOT NULL,
            formato VARCHAR(20) NOT NULL,
            filtros TEXT NULL,
            gerado_em DATETIME NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_rel_user (usuario_id),
            INDEX idx_rel_data (gerado_em),
            CONSTRAINT fk_rel_user FOREIGN KEY (usuario_id) REFERENCES {$tables['usuarios']}(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
    ];
} else {
    // SQLite
    $sqlStatements = [
        "CREATE TABLE IF NOT EXISTS {$tables['parceiros']} (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome TEXT NOT NULL,
            tipo TEXT NOT NULL DEFAULT 'academia',
            documento TEXT NULL,
            cidade TEXT NULL,
            contato TEXT NULL,
            telefone TEXT NULL,
            email TEXT NULL,
            status TEXT NOT NULL DEFAULT 'ativo',
            ticket_medio REAL NULL,
            atualizado_em TEXT NULL,
            created_at TEXT DEFAULT (datetime('now')),
            updated_at TEXT DEFAULT (datetime('now'))
        );",
        "CREATE TABLE IF NOT EXISTS {$tables['consig_produtos']} (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            parceiro_id INTEGER NOT NULL,
            produto TEXT NOT NULL,
            sku TEXT NULL,
            categoria TEXT NULL,
            lote TEXT NULL,
            nf TEXT NULL,
            estoque INTEGER NOT NULL DEFAULT 0,
            minimo INTEGER NOT NULL DEFAULT 0,
            vendido_mes INTEGER NOT NULL DEFAULT 0,
            devolucao INTEGER NOT NULL DEFAULT 0,
            prazo_dev TEXT NULL,
            created_at TEXT DEFAULT (datetime('now')),
            updated_at TEXT DEFAULT (datetime('now')),
            FOREIGN KEY(parceiro_id) REFERENCES {$tables['parceiros']}(id) ON DELETE CASCADE
        );",
        "CREATE TABLE IF NOT EXISTS {$tables['consig_movs']} (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            parceiro_id INTEGER NOT NULL,
            tipo TEXT NOT NULL,
            descricao TEXT NULL,
            produto TEXT NOT NULL,
            sku TEXT NULL,
            quantidade INTEGER NOT NULL DEFAULT 0,
            valor REAL NULL,
            usuario TEXT NULL,
            data TEXT NOT NULL,
            created_at TEXT DEFAULT (datetime('now')),
            FOREIGN KEY(parceiro_id) REFERENCES {$tables['parceiros']}(id) ON DELETE CASCADE
        );",
        "CREATE TABLE IF NOT EXISTS {$tables['movimentacoes']} (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            tipo TEXT NOT NULL,
            parceiro_id INTEGER NULL,
            produto TEXT NOT NULL,
            quantidade INTEGER NOT NULL DEFAULT 0,
            nf_ref TEXT NULL,
            lote TEXT NULL,
            datahora TEXT NOT NULL,
            observacao TEXT NULL,
            created_at TEXT DEFAULT (datetime('now')),
            FOREIGN KEY(parceiro_id) REFERENCES {$tables['parceiros']}(id) ON DELETE SET NULL
        );",
        "CREATE TABLE IF NOT EXISTS {$tables['auditorias']} (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            status TEXT NOT NULL DEFAULT 'pendente',
            descricao TEXT NULL,
            created_at TEXT DEFAULT (datetime('now')),
            updated_at TEXT DEFAULT (datetime('now'))
        );",
        "CREATE TABLE IF NOT EXISTS {$tables['auditoria_itens']} (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            auditoria_id INTEGER NOT NULL,
            produto TEXT NOT NULL,
            local TEXT NULL,
            qtde_sistema INTEGER NOT NULL DEFAULT 0,
            qtde_fisica INTEGER NULL,
            status TEXT NOT NULL DEFAULT 'pendente',
            created_at TEXT DEFAULT (datetime('now')),
            FOREIGN KEY(auditoria_id) REFERENCES {$tables['auditorias']}(id) ON DELETE CASCADE
        );",
        "CREATE TABLE IF NOT EXISTS {$tables['auditoria_historico']} (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            auditoria_id INTEGER NOT NULL,
            acao TEXT NOT NULL,
            descricao TEXT NULL,
            usuario TEXT NULL,
            data TEXT NOT NULL,
            created_at TEXT DEFAULT (datetime('now')),
            FOREIGN KEY(auditoria_id) REFERENCES {$tables['auditorias']}(id) ON DELETE CASCADE
        );",
        "CREATE TABLE IF NOT EXISTS {$tables['relatorios_log']} (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            usuario_id INTEGER NULL,
            nome TEXT NOT NULL,
            formato TEXT NOT NULL,
            filtros TEXT NULL,
            gerado_em TEXT NOT NULL,
            created_at TEXT DEFAULT (datetime('now')),
            FOREIGN KEY(usuario_id) REFERENCES {$tables['usuarios']}(id) ON DELETE SET NULL
        );",
    ];
}

foreach ($sqlStatements as $statement) {
    $pdo->exec($statement);
}
