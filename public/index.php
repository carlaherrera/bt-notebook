<?php
// public/index.php
// Front controller da aplicação – toda requisição passa por aqui.

declare(strict_types=1);

// Caminho base do projeto (volta uma pasta a partir de /public)
define('BASE_PATH', dirname(__DIR__));

$lockFile = BASE_PATH . '/install.lock';
$configLocal = BASE_PATH . '/config.local.php';

if (!is_file($lockFile) || !is_file($configLocal)) {
    $scheme = 'http';
    if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        $scheme = explode(',', $_SERVER['HTTP_X_FORWARDED_PROTO'])[0];
    } elseif (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        $scheme = 'https';
    }

    $host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');
    $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
    $scriptDir = $scriptDir === DIRECTORY_SEPARATOR ? '' : $scriptDir;
    $scriptDir = rtrim(str_replace('\\', '/', $scriptDir), '/');
    $basePathUri = $scriptDir === '' ? '' : $scriptDir;

    $baseUrl = rtrim($scheme . '://' . $host . $basePathUri, '/');
    if ($baseUrl === '') {
        $baseUrl = $scheme . '://' . $host;
    }
    $installerUrl = rtrim($baseUrl, '/') . '/install/';

    $escape = static function (string $value): string {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    };

    $requirements = [
        [
            'label' => 'config.local.php',
            'description' => 'Gerado após salvar os dados do banco.',
            'ok' => is_file($configLocal),
        ],
        [
            'label' => 'install.lock',
            'description' => 'Bloqueia o instalador quando tudo estiver pronto.',
            'ok' => is_file($lockFile),
        ],
    ];

    $baseUrlDisplay = $baseUrl . '/';

    ?><!doctype html>
    <html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sistema em instalação</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
        <style>
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }
            body {
                min-height: 100vh;
                background: #0f172a;
                color: #e2e8f0;
                font-family: 'Space Grotesk', 'Segoe UI', system-ui, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 32px 16px;
            }
            .wrapper {
                width: min(760px, 100%);
                background: #0b1324;
                border: 1px solid rgba(148, 163, 184, 0.25);
                border-radius: 28px;
                padding: 40px;
                box-shadow: 0 30px 60px rgba(2, 6, 23, 0.6);
                text-align: center;
            }
            .wrapper h1 {
                font-size: clamp(2rem, 4vw, 2.8rem);
                color: #f8fafc;
                margin-bottom: 12px;
            }
            .wrapper p {
                color: #94a3b8;
                margin-bottom: 28px;
                line-height: 1.6;
            }
            .info {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 18px;
                margin-bottom: 28px;
            }
            .info-card {
                background: #111b2f;
                border: 1px solid rgba(148, 163, 184, 0.3);
                border-radius: 16px;
                padding: 18px;
                text-align: left;
            }
            .info-card label {
                font-size: 0.75rem;
                letter-spacing: 0.2em;
                text-transform: uppercase;
                color: #64748b;
                display: block;
                margin-bottom: 10px;
            }
            .info-card code {
                font-size: 1rem;
                color: #e0f2fe;
                word-break: break-all;
            }
            .requirements {
                display: flex;
                flex-direction: column;
                gap: 12px;
                margin-bottom: 28px;
            }
            .req {
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: #0f1a2f;
                border-radius: 12px;
                padding: 14px 18px;
                border: 1px solid rgba(148, 163, 184, 0.2);
            }
            .req strong {
                font-size: 1rem;
            }
            .req span {
                font-size: 0.9rem;
                color: #94a3b8;
            }
            .status {
                font-weight: 600;
                letter-spacing: 0.2em;
                font-size: 0.85rem;
                text-transform: uppercase;
            }
            .status.ok {
                color: #34d399;
            }
            .status.pending {
                color: #fcd34d;
            }
            .actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                justify-content: center;
            }
            .btn {
                text-decoration: none;
                padding: 14px 24px;
                border-radius: 999px;
                font-weight: 600;
                font-size: 0.95rem;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }
            .btn.primary {
                background: linear-gradient(120deg, #6366f1, #a855f7);
                color: white;
            }
            .btn.secondary {
                border: 1px solid rgba(148, 163, 184, 0.5);
                color: #e2e8f0;
            }
            .btn:hover {
                transform: translateY(-2px);
            }
            @media (max-width: 520px) {
                .wrapper {
                    padding: 28px 20px;
                }
                .req {
                    flex-direction: column;
                    gap: 6px;
                    align-items: flex-start;
                }
                .actions {
                    flex-direction: column;
                }
            }
        </style>
    </head>
    <body>
        <section class="wrapper">
            <h1>Instalação pendente</h1>
            <p>Finalize a configuração do banco e do usuário admin antes de usar o sistema.</p>

            <div class="info">
                <div class="info-card">
                    <label>Base URL</label>
                    <code><?php echo $escape($baseUrlDisplay); ?></code>
                </div>
                <div class="info-card">
                    <label>Instalador</label>
                    <code><?php echo $escape($installerUrl); ?></code>
                </div>
            </div>

            <div class="requirements">
                <?php foreach ($requirements as $req): ?>
                    <div class="req">
                        <div>
                            <strong><?php echo $escape($req['label']); ?></strong><br>
                            <span><?php echo $escape($req['description']); ?></span>
                        </div>
                        <div class="status <?php echo $req['ok'] ? 'ok' : 'pending'; ?>">
                            <?php echo $req['ok'] ? 'Pronto' : 'Pendente'; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="actions">
                <a class="btn primary" href="<?php echo $escape($installerUrl); ?>">
                    Abrir instalador
                </a>
                <a class="btn secondary" href="<?php echo $escape($baseUrl); ?>">
                    Recarregar base
                </a>
            </div>
        </section>
    </body>
    </html>
    <?php
    exit;
}

// Carrega o bootstrap com autoload, sessão, constantes etc.
require BASE_PATH . '/bootstrap.php';

use App\Core\Router;

// Instancia o Router principal
$router = new Router();

// Carrega o arquivo de rotas e registra todas as rotas na instância do Router
require BASE_PATH . '/routes.php';

// Descobre a URL e o método HTTP atuais
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Dispara o roteamento (vai chamar o Controller/Método correspondente)
$router->dispatch($uri, $method);
