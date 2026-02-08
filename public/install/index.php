<?php

declare(strict_types=1);

$basePath = dirname(__DIR__, 2);
if (!defined('BASE_PATH')) {
    define('BASE_PATH', $basePath);
}

$lockFile = BASE_PATH . '/install.lock';
if (is_file($lockFile)) {
    http_response_code(403);
    echo 'Instalador bloqueado.';
    exit;
}

function h(string $v): string
{
    return htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function boolText(bool $v): string
{
    return $v ? 'OK' : 'FALHOU';
}

function ensureWritableDir(string $absoluteDir): array
{
    $created = false;
    if (!is_dir($absoluteDir)) {
        $created = @mkdir($absoluteDir, 0775, true);
    }

    $writable = is_writable($absoluteDir);
    if (!$writable && is_dir($absoluteDir)) {
        @chmod($absoluteDir, 0775);
        $writable = is_writable($absoluteDir);
    }

    return [
        'path' => $absoluteDir,
        'exists' => is_dir($absoluteDir),
        'created' => $created,
        'writable' => $writable,
    ];
}

function requirementsReport(): array
{
    $phpOk = PHP_VERSION_ID >= 80100;

    $extensions = [
        'pdo' => extension_loaded('pdo'),
        'pdo_mysql' => extension_loaded('pdo_mysql'),
        'fileinfo' => extension_loaded('fileinfo'),
        'gd' => extension_loaded('gd'),
        'openssl' => extension_loaded('openssl'),
    ];

    $functions = [
        'password_hash' => function_exists('password_hash'),
        'password_verify' => function_exists('password_verify'),
        'random_bytes' => function_exists('random_bytes'),
        'imagewebp' => function_exists('imagewebp'),
    ];

    $paths = [
        'base' => BASE_PATH,
        'config_local' => BASE_PATH . '/config.local.php',
        'env' => BASE_PATH . '/.env',
        'lock' => BASE_PATH . '/install.lock',
        'migrations' => BASE_PATH . '/database/migrations',
        'uploads' => BASE_PATH . '/public/uploads',
        'uploads_config' => BASE_PATH . '/public/uploads/configuracoes',
    ];

    $canWriteBase = is_writable(BASE_PATH);
    $filesWritable = [
        'config_local' => ($canWriteBase && (!file_exists($paths['config_local']) || is_writable($paths['config_local']))),
        'env' => ($canWriteBase && (!file_exists($paths['env']) || is_writable($paths['env']))),
        'lock' => ($canWriteBase && (!file_exists($paths['lock']) || is_writable($paths['lock']))),
    ];

    $dirs = [
        ensureWritableDir($paths['uploads']),
        ensureWritableDir($paths['uploads_config']),
    ];

    $migrationsReadable = is_dir($paths['migrations']) && is_readable($paths['migrations']);

    $ok = $phpOk
        && !in_array(false, $extensions, true)
        && !in_array(false, $functions, true)
        && $migrationsReadable;

    if (!in_array(false, $filesWritable, true)) {
        // ok
    } else {
        $ok = false;
    }

    foreach ($dirs as $d) {
        if (!$d['exists'] || !$d['writable']) {
            $ok = false;
            break;
        }
    }

    return [
        'ok' => $ok,
        'php_ok' => $phpOk,
        'php_version' => PHP_VERSION,
        'extensions' => $extensions,
        'functions' => $functions,
        'paths' => $paths,
        'dirs' => $dirs,
        'migrations_readable' => $migrationsReadable,
        'base_writable' => $canWriteBase,
        'files_writable' => $filesWritable,
    ];
}

function validateAdminPassword(string $password, string $confirm): void
{
    if ($password !== $confirm) {
        throw new RuntimeException('As senhas do admin não coincidem.');
    }

    if (mb_strlen($password) < 10) {
        throw new RuntimeException('A senha do admin deve ter no mínimo 10 caracteres.');
    }

    $hasUpper = (bool) preg_match('/[A-Z]/', $password);
    $hasLower = (bool) preg_match('/[a-z]/', $password);
    $hasDigit = (bool) preg_match('/\d/', $password);
    $hasSymbol = (bool) preg_match('/[^a-zA-Z\d]/', $password);

    if (!($hasUpper && $hasLower && $hasDigit && $hasSymbol)) {
        throw new RuntimeException('A senha do admin deve conter: maiúscula, minúscula, número e símbolo.');
    }
}

function writeConfigLocal(array $config): void
{
    $target = BASE_PATH . '/config.local.php';
    $php = "<?php\n\nreturn " . var_export($config, true) . ";\n";
    $ok = @file_put_contents($target, $php, LOCK_EX);
    if ($ok === false) {
        throw new RuntimeException('Falha ao escrever config.local.php');
    }
}

function testMysql(array $db): void
{
    $dsn = "mysql:host={$db['host']};dbname={$db['database']};charset={$db['charset']}";
    $pdo = new PDO($dsn, $db['username'], $db['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    if (!empty($db['collation'])) {
        $pdo->exec("SET NAMES {$db['charset']} COLLATE {$db['collation']}");
    }

    // Teste de permissões (CREATE/INSERT/DROP)
    $tmp = 'install_check_' . bin2hex(random_bytes(4));
    $pdo->exec("CREATE TABLE `{$tmp}` (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, v VARCHAR(10) NOT NULL) ENGINE=InnoDB");
    $stmt = $pdo->prepare("INSERT INTO `{$tmp}` (v) VALUES (:v)");
    $stmt->execute(['v' => 'ok']);
    $pdo->exec("DROP TABLE `{$tmp}`");
}

function removeDirRecursive(string $dir): bool
{
    if (!is_dir($dir)) {
        return true;
    }
    $items = scandir($dir);
    if (!is_array($items)) {
        return false;
    }
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        if (is_dir($path)) {
            if (!removeDirRecursive($path)) {
                return false;
            }
        } else {
            if (!@unlink($path)) {
                return false;
            }
        }
    }
    return @rmdir($dir);
}

function runMigrations(): void
{
    require_once BASE_PATH . '/bootstrap.php';
    App\Core\Database::refresh();

    $migrationsPath = BASE_PATH . '/database/migrations';
    $files = scandir($migrationsPath);
    if (!is_array($files)) {
        throw new RuntimeException('Falha ao listar migrations.');
    }

    foreach ($files as $file) {
        if (substr($file, -4) === '.php') {
            require $migrationsPath . '/' . $file;
        }
    }
}

function createAdmin(string $nome, string $email, string $senha): void
{
    require_once BASE_PATH . '/bootstrap.php';

    $repo = new App\Repositories\UserRepository();

    $existing = $repo->findByEmail($email);
    if ($existing) {
        return;
    }

    $repo->insert([
        'nome' => $nome,
        'sobrenome' => null,
        'email' => $email,
        'senha' => password_hash($senha, PASSWORD_BCRYPT),
        'role' => 'admin',
        'status' => 1,
        'whatsapp' => null,
        'imagem_perfil' => null,
        'created_at' => date('Y-m-d H:i:s'),
    ]);
}

$error = '';
$success = '';

$report = requirementsReport();

$values = [
    'db_host' => $_POST['db_host'] ?? 'localhost',
    'db_name' => $_POST['db_name'] ?? '',
    'db_user' => $_POST['db_user'] ?? '',
    'db_pass' => $_POST['db_pass'] ?? '',
    'db_charset' => $_POST['db_charset'] ?? 'utf8mb4',
    'db_collation' => $_POST['db_collation'] ?? 'utf8mb4_unicode_ci',
    'table_prefix' => $_POST['table_prefix'] ?? '',
    'admin_nome' => $_POST['admin_nome'] ?? 'Admin',
    'admin_email' => $_POST['admin_email'] ?? '',
    'admin_senha' => $_POST['admin_senha'] ?? '',
    'admin_senha_confirm' => $_POST['admin_senha_confirm'] ?? '',
    'app_env' => $_POST['app_env'] ?? 'production',
    'remove_installer' => $_POST['remove_installer'] ?? '1',
];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    try {
        if (!$report['ok']) {
            throw new RuntimeException('Requisitos/permissões não atendidos. Ajuste antes de instalar.');
        }

        if (trim((string)$values['db_name']) === '' || trim((string)$values['db_user']) === '' || trim((string)$values['admin_email']) === '' || trim((string)$values['admin_senha']) === '' || trim((string)$values['admin_senha_confirm']) === '') {
            throw new RuntimeException('Preencha os campos obrigatórios.');
        }

        validateAdminPassword((string)$values['admin_senha'], (string)$values['admin_senha_confirm']);

        $db = [
            'host' => (string)$values['db_host'],
            'database' => (string)$values['db_name'],
            'username' => (string)$values['db_user'],
            'password' => (string)$values['db_pass'],
            'charset' => (string)$values['db_charset'],
            'collation' => (string)$values['db_collation'],
        ];

        $prefix = (string) $values['table_prefix'];
        $prefix = trim($prefix);
        if ($prefix !== '' && !preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $prefix)) {
            throw new RuntimeException('Prefixo de tabelas inválido. Use letras/números/underscore e comece com letra ou underscore.');
        }

        testMysql($db);

        writeConfigLocal([
            'database' => [
                'driver' => 'mysql',
                'table_prefix' => $prefix,
                'sqlite' => [
                    'database' => BASE_PATH . '/database/database.sqlite',
                ],
                'mysql' => $db,
            ],
        ]);

        $envFile = BASE_PATH . '/.env';
        $env = "APP_ENV=" . $values['app_env'] . "\n";
        $envOk = @file_put_contents($envFile, $env, LOCK_EX);
        if ($envOk === false) {
            throw new RuntimeException('Falha ao escrever .env');
        }

        runMigrations();
        createAdmin((string)$values['admin_nome'], (string)$values['admin_email'], (string)$values['admin_senha']);

        $lockOk = @file_put_contents($lockFile, 'installed=' . date('c') . "\n", LOCK_EX);
        if ($lockOk === false) {
            throw new RuntimeException('Falha ao escrever install.lock');
        }

        if ((string)$values['remove_installer'] === '1') {
            $installerDir = dirname(__FILE__);
            @removeDirRecursive($installerDir);
        }

        $success = 'Instalação concluída.';
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

?><!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Instalador</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script defer src="https://unpkg.com/lucide@latest"></script>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
      color: #1f2937;
      min-height: 100vh;
      padding: 40px 16px;
    }
    .wrap { max-width: 900px; margin: 0 auto; }
    .header {
      text-align: center;
      margin-bottom: 40px;
    }
    .header h1 {
      font-size: 32px;
      font-weight: 700;
      color: #111827;
      margin-bottom: 8px;
    }
    .header p {
      font-size: 14px;
      color: #6b7280;
    }
    .card {
      background: white;
      border-radius: 16px;
      padding: 32px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 10px 15px -3px rgba(0,0,0,0.05);
    }
    .section-title {
      font-size: 16px;
      font-weight: 600;
      color: #111827;
      margin-top: 28px;
      margin-bottom: 16px;
      padding-bottom: 12px;
      border-bottom: 2px solid #e5e7eb;
    }
    .section-title:first-of-type { margin-top: 0; }
    .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .grid.full { grid-template-columns: 1fr; }
    .form-group { display: flex; flex-direction: column; }
    label {
      font-size: 13px;
      font-weight: 500;
      color: #374151;
      margin-bottom: 6px;
    }
    .input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }
    input, select {
      width: 100%;
      padding: 10px 12px;
      border-radius: 8px;
      border: 1px solid #d1d5db;
      background: #f9fafb;
      color: #111827;
      font-size: 14px;
      transition: all 0.2s;
    }
    input:focus, select:focus {
      outline: none;
      border-color: #2563eb;
      background: white;
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    input[type="password"] { font-family: monospace; }
    .gen-btn {
      position: absolute;
      right: 8px;
      background: none;
      border: none;
      color: #6b7280;
      cursor: pointer;
      padding: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: color 0.2s;
    }
    .gen-btn:hover { color: #2563eb; }
    .status-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
      margin-bottom: 20px;
    }
    .status-item {
      background: #f9fafb;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      padding: 12px;
    }
    .status-label { font-size: 12px; color: #6b7280; font-weight: 500; }
    .status-value { font-size: 14px; color: #111827; font-weight: 600; margin-top: 4px; }
    .status-ok { color: #059669; }
    .status-fail { color: #dc2626; }
    .checklist {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 12px;
      font-size: 13px;
    }
    .check-item {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 8px;
      background: #f9fafb;
      border-radius: 6px;
      border: 1px solid #e5e7eb;
    }
    .check-icon { width: 16px; height: 16px; flex-shrink: 0; }
    .check-ok { color: #059669; }
    .check-fail { color: #dc2626; }
    .msg {
      padding: 14px 16px;
      border-radius: 8px;
      margin-bottom: 16px;
      font-size: 14px;
    }
    .err {
      background: #fee2e2;
      border: 1px solid #fecaca;
      color: #991b1b;
    }
    .ok {
      background: #dcfce7;
      border: 1px solid #bbf7d0;
      color: #166534;
    }
    .info {
      background: #dbeafe;
      border: 1px solid #bfdbfe;
      color: #1e40af;
    }
    .btn {
      margin-top: 24px;
      background: #2563eb;
      border: 0;
      color: white;
      padding: 12px 24px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
      transition: all 0.2s;
      width: 100%;
    }
    .btn:hover { background: #1d4ed8; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3); }
    .btn:disabled { background: #d1d5db; cursor: not-allowed; }
    .hint { font-size: 12px; color: #6b7280; margin-top: 4px; }
    @media (max-width: 720px) {
      .grid { grid-template-columns: 1fr; }
      .card { padding: 20px; }
      .header h1 { font-size: 24px; }
    }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="header">
      <h1>Instalador</h1>
      <p>Configure seu banco de dados e crie o primeiro usuário admin</p>
    </div>
    <div class="card">

      <div class="status-grid">
        <div class="status-item">
          <div class="status-label">PHP</div>
          <div class="status-value <?php echo $report['php_ok'] ? 'status-ok' : 'status-fail'; ?>">
            <?php echo h($report['php_version']); ?>
          </div>
        </div>
        <div class="status-item">
          <div class="status-label">Status Geral</div>
          <div class="status-value <?php echo $report['ok'] ? 'status-ok' : 'status-fail'; ?>">
            <?php echo $report['ok'] ? '✓ Pronto' : '✗ Pendências'; ?>
          </div>
        </div>
      </div>

      <div class="section-title">Extensões PHP</div>
      <div class="checklist">
        <?php foreach ($report['extensions'] as $k => $v): ?>
          <div class="check-item">
            <span class="check-icon <?php echo $v ? 'check-ok' : 'check-fail'; ?>">
              <?php echo $v ? '✓' : '✗'; ?>
            </span>
            <span><?php echo h($k); ?></span>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="section-title">Funções PHP</div>
      <div class="checklist">
        <?php foreach ($report['functions'] as $k => $v): ?>
          <div class="check-item">
            <span class="check-icon <?php echo $v ? 'check-ok' : 'check-fail'; ?>">
              <?php echo $v ? '✓' : '✗'; ?>
            </span>
            <span><?php echo h($k); ?></span>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="section-title">Permissões de Pastas</div>
      <div class="checklist">
        <?php foreach ($report['dirs'] as $d): ?>
          <div class="check-item">
            <span class="check-icon <?php echo ($d['exists'] && $d['writable']) ? 'check-ok' : 'check-fail'; ?>">
              <?php echo ($d['exists'] && $d['writable']) ? '✓' : '✗'; ?>
            </span>
            <span><?php echo basename(h($d['path'])); ?></span>
          </div>
        <?php endforeach; ?>
        <div class="check-item">
          <span class="check-icon <?php echo $report['migrations_readable'] ? 'check-ok' : 'check-fail'; ?>">
            <?php echo $report['migrations_readable'] ? '✓' : '✗'; ?>
          </span>
          <span>migrations</span>
        </div>
      </div>

      <div class="section-title">Arquivos de Configuração</div>
      <div class="checklist">
        <div class="check-item">
          <span class="check-icon <?php echo $report['base_writable'] ? 'check-ok' : 'check-fail'; ?>">
            <?php echo $report['base_writable'] ? '✓' : '✗'; ?>
          </span>
          <span>Raiz do projeto</span>
        </div>
        <?php foreach ($report['files_writable'] as $k => $v): ?>
          <div class="check-item">
            <span class="check-icon <?php echo $v ? 'check-ok' : 'check-fail'; ?>">
              <?php echo $v ? '✓' : '✗'; ?>
            </span>
            <span><?php echo h($k); ?></span>
          </div>
        <?php endforeach; ?>
      </div>

      <?php if ($error !== ''): ?>
        <div class="msg err"><?php echo h($error); ?></div>
      <?php endif; ?>

      <?php if ($success !== ''): ?>
        <div class="msg ok"><?php echo h($success); ?></div>
      <?php endif; ?>

      <form method="post" autocomplete="off">
        <div class="section-title">Banco de Dados MySQL</div>
        <div class="grid">
          <div class="form-group">
            <label for="db_host">Host MySQL *</label>
            <input id="db_host" name="db_host" value="<?php echo h((string)$values['db_host']); ?>" placeholder="localhost" required />
            <div class="hint">Geralmente é localhost ou 127.0.0.1</div>
          </div>
          <div class="form-group">
            <label for="db_name">Nome do banco *</label>
            <input id="db_name" name="db_name" value="<?php echo h((string)$values['db_name']); ?>" placeholder="meu_banco" required />
            <div class="hint">Nome do banco de dados criado</div>
          </div>
          <div class="form-group">
            <label for="db_user">Usuário *</label>
            <input id="db_user" name="db_user" value="<?php echo h((string)$values['db_user']); ?>" placeholder="root" required />
            <div class="hint">Usuário do MySQL com permissões</div>
          </div>
          <div class="form-group">
            <label for="db_pass">Senha do MySQL</label>
            <input id="db_pass" name="db_pass" type="password" value="<?php echo h((string)$values['db_pass']); ?>" placeholder="(deixe em branco se não houver)" />
            <div class="hint">Deixe em branco se não houver senha</div>
          </div>
          <div class="form-group">
            <label for="db_charset">Charset</label>
            <input id="db_charset" name="db_charset" value="<?php echo h((string)$values['db_charset']); ?>" placeholder="utf8mb4" />
            <div class="hint">Padrão: utf8mb4</div>
          </div>
          <div class="form-group">
            <label for="db_collation">Collation</label>
            <input id="db_collation" name="db_collation" value="<?php echo h((string)$values['db_collation']); ?>" placeholder="utf8mb4_unicode_ci" />
            <div class="hint">Padrão: utf8mb4_unicode_ci</div>
          </div>
        </div>

        <div class="grid full">
          <div class="form-group">
            <label for="table_prefix">Prefixo das tabelas (opcional)</label>
            <input id="table_prefix" name="table_prefix" value="<?php echo h((string)$values['table_prefix']); ?>" placeholder="ex: app_" />
            <div class="hint">Use apenas letras, números e underscore. Deixe em branco para sem prefixo.</div>
          </div>
        </div>

        <div class="section-title">Primeiro Usuário Admin</div>
        <div class="grid">
          <div class="form-group">
            <label for="admin_nome">Nome do admin *</label>
            <input id="admin_nome" name="admin_nome" value="<?php echo h((string)$values['admin_nome']); ?>" placeholder="João Silva" required />
          </div>
          <div class="form-group">
            <label for="admin_email">Email do admin *</label>
            <input id="admin_email" name="admin_email" type="email" value="<?php echo h((string)$values['admin_email']); ?>" placeholder="admin@exemplo.com" required />
          </div>
        </div>

        <div class="grid">
          <div class="form-group">
            <label for="admin_senha">Senha do admin *</label>
            <div class="input-wrapper">
              <input id="admin_senha" name="admin_senha" type="password" value="<?php echo h((string)$values['admin_senha']); ?>" placeholder="Mínimo 10 caracteres" required />
              <button type="button" class="gen-btn" onclick="generatePassword('admin_senha')" title="Gerar senha">
                <i data-lucide="refresh-cw" style="width:18px;height:18px;"></i>
              </button>
            </div>
            <div class="hint">Mínimo 10 caracteres, com maiúscula, minúscula, número e símbolo</div>
          </div>
          <div class="form-group">
            <label for="admin_senha_confirm">Confirmar senha *</label>
            <input id="admin_senha_confirm" name="admin_senha_confirm" type="password" value="<?php echo h((string)$values['admin_senha_confirm']); ?>" placeholder="Repita a senha" required />
            <div class="hint">Deve ser idêntica à senha acima</div>
          </div>
        </div>

        <div class="section-title">Configurações Adicionais</div>
        <div class="grid">
          <div class="form-group">
            <label for="app_env">Ambiente (APP_ENV)</label>
            <select id="app_env" name="app_env">
              <?php $env = (string)$values['app_env']; ?>
              <option value="production" <?php echo $env==='production'?'selected':''; ?>>production</option>
              <option value="development" <?php echo $env==='development'?'selected':''; ?>>development</option>
            </select>
            <div class="hint">production para servidor final, development para testes</div>
          </div>
          <div class="form-group">
            <label for="remove_installer">Remover instalador após concluir</label>
            <select id="remove_installer" name="remove_installer">
              <?php $ri = (string)$values['remove_installer']; ?>
              <option value="1" <?php echo $ri==='1'?'selected':''; ?>>Sim (recomendado)</option>
              <option value="0" <?php echo $ri==='0'?'selected':''; ?>>Não</option>
            </select>
            <div class="hint">Recomendado remover public/install/ por segurança</div>
          </div>
        </div>

        <button class="btn" type="submit">Instalar</button>
        <div class="hint" style="text-align:center;margin-top:16px;">
          Após instalar, será criado um arquivo <strong>install.lock</strong> na raiz e o instalador ficará bloqueado.
        </div>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      lucide.createIcons();
    });

    function generatePassword(fieldId) {
      const length = 16;
      const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      const lowercase = 'abcdefghijklmnopqrstuvwxyz';
      const numbers = '0123456789';
      const symbols = '!@#$%^&*()_+-=[]{}|;:,.<>?';
      
      const allChars = uppercase + lowercase + numbers + symbols;
      let password = '';
      
      password += uppercase[Math.floor(Math.random() * uppercase.length)];
      password += lowercase[Math.floor(Math.random() * lowercase.length)];
      password += numbers[Math.floor(Math.random() * numbers.length)];
      password += symbols[Math.floor(Math.random() * symbols.length)];
      
      for (let i = password.length; i < length; i++) {
        password += allChars[Math.floor(Math.random() * allChars.length)];
      }
      
      password = password.split('').sort(() => Math.random() - 0.5).join('');
      
      const field = document.getElementById(fieldId);
      field.value = password;
      field.type = 'text';
      
      setTimeout(() => {
        field.type = 'password';
      }, 3000);
    }
  </script>
</body>
</html>