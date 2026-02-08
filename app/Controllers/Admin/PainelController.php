<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;

class PainelController extends Controller
{
    public function __construct()
    {
        $this->requireRole('admin');
        $this->requireActive();
    }

    public function index(): void
    {
        $pdo = Database::getConnection();
        $usersTable = Database::table('usuarios');
        $attachmentsTable = Database::table('attachments');
        $driver = Database::getDriver();

        // Contagens básicas
        $countsStmt = $pdo->query("
            SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS ativos,
                SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) AS inativos
            FROM {$usersTable}
        ");
        $counts = $countsStmt->fetch(\PDO::FETCH_ASSOC) ?: ['total' => 0, 'ativos' => 0, 'inativos' => 0];

        // Contagem por papel
        $rolesStmt = $pdo->query("SELECT role, COUNT(*) AS total FROM {$usersTable} GROUP BY role");
        $rolesRaw = $rolesStmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        $roles = [
            'admin' => 0,
            'colaborador' => 0,
            'cliente' => 0,
        ];
        foreach ($rolesRaw as $r) {
            $role = $r['role'] ?? '';
            if (isset($roles[$role])) {
                $roles[$role] = (int)$r['total'];
            }
        }

        // Usuários por dia (últimos 7 dias) para gráfico simples
        if ($driver === 'mysql') {
            $activitySql = "
                SELECT DATE(created_at) as dia, COUNT(*) as total
                FROM {$usersTable}
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                GROUP BY DATE(created_at)
                ORDER BY dia ASC
            ";
        } else {
            $activitySql = "
                SELECT DATE(created_at) as dia, COUNT(*) as total
                FROM {$usersTable}
                WHERE date(created_at) >= date('now','-6 day')
                GROUP BY DATE(created_at)
                ORDER BY dia ASC
            ";
        }
        $activityStmt = $pdo->query($activitySql);
        $activity = $activityStmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];

        // Usuários recentes
        $recentStmt = $pdo->query("
            SELECT nome, email, role, status, created_at 
            FROM {$usersTable}
            ORDER BY created_at DESC
            LIMIT 5
        ");
        $recentUsers = $recentStmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];

        // Anexos recentes e total
        $attachmentsCount = 0;
        $attachmentsRecent = [];
        try {
            $attachmentsCountStmt = $pdo->query("SELECT COUNT(*) AS total FROM {$attachmentsTable}");
            $attachmentsCountRow = $attachmentsCountStmt->fetch(\PDO::FETCH_ASSOC);
            $attachmentsCount = (int)($attachmentsCountRow['total'] ?? 0);

            $attachmentsRecentStmt = $pdo->query("
                SELECT filename, mime_type, size, created_at
                FROM {$attachmentsTable}
                ORDER BY created_at DESC
                LIMIT 5
            ");
            $attachmentsRecent = $attachmentsRecentStmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\Throwable $e) {
            // tabela pode não existir; ignora
        }

        // Configurações (primeira linha de settings)
        $settingsRow = null;
        try {
            $settingsTable = Database::table('settings');
            $settingsStmt = $pdo->query("SELECT org_name, favicon_path, created_at, updated_at FROM {$settingsTable} LIMIT 1");
            $settingsRow = $settingsStmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        } catch (\Throwable $e) {
            // settings pode não existir
        }

        // Logs recentes (último arquivo do dia)
        $logLines = [];
        try {
            $logFile = BASE_PATH . '/logs/app-' . date('Y-m-d') . '.log';
            if (is_file($logFile)) {
                $fileLines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                if ($fileLines !== false) {
                    $logLines = array_slice($fileLines, -5);
                }
            }
        } catch (\Throwable $e) {
            // sem log ou erro de leitura
        }

        // Usuários online (sessões ativas recentes)
        $onlineUsers = 0;
        try {
            $sessionPath = session_save_path();
            if (!$sessionPath) {
                $sessionPath = sys_get_temp_dir();
            }
            $files = @glob(rtrim($sessionPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'sess_*');
            if ($files !== false) {
                $threshold = time() - (15 * 60); // últimos 15 minutos
                foreach ($files as $file) {
                    $mtime = @filemtime($file);
                    if ($mtime !== false && $mtime >= $threshold) {
                        $onlineUsers++;
                    }
                }
            }
        } catch (\Throwable $e) {
            // fallback silencioso se não conseguir ler sessões
        }

        $stats = [
            'totalUsuarios' => (int)($counts['total'] ?? 0),
            'ativos' => (int)($counts['ativos'] ?? 0),
            'inativos' => (int)($counts['inativos'] ?? 0),
            'roles' => $roles,
            'attachmentsTotal' => $attachmentsCount,
            'activity' => $activity,
            'attachmentsRecent' => $attachmentsRecent,
            'settings' => $settingsRow,
            'logs' => $logLines,
            'online' => $onlineUsers,
        ];

        $this->layout('layouts/painel', 'admin/painel/index', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
        ]);
    }
}
