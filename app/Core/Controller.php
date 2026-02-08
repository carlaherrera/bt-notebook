<?php

declare(strict_types=1);

namespace App\Core;

class Controller
{
    protected function validateCsrf(): void
    {
        if (!Security::validateCsrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            exit('Falha de verificação CSRF.');
        }
    }

    protected function requireAuth(): void
    {
        if (!Auth::check()) {
            $this->redirect('/entrar');
        }
    }

    protected function requireRole(string|array $roles): void
    {
        $this->requireAuth();
        $rolesList = (array) $roles;
        $userRole = Auth::role();
        if (!in_array($userRole, $rolesList, true)) {
            http_response_code(403);
            exit('Acesso negado');
        }
    }

    protected function requireActive(): void
    {
        $this->requireAuth();
        $user = Auth::user();
        if (!$user || !(int)($user->status ?? 0)) {
            $pdo = Database::getConnection();
            $settingsTable = Database::table('settings');
            $stmt = $pdo->query("SELECT whatsapp FROM {$settingsTable} LIMIT 1");
            $settings = $stmt->fetch(\PDO::FETCH_ASSOC);
            $whatsapp = $settings['whatsapp'] ?? '';
            $this->layout('layouts/base', 'usuario-inativo', ['whatsapp' => $whatsapp]);
            exit;
        }
    }

    protected function view(string $viewPath, array $data = []): void
    {
        View::render($viewPath, $data);
    }

    protected function layout(string $layout, string $viewPath, array $data = []): void
    {
        View::layout($layout, $viewPath, $data);
    }

    protected function redirect(string $route): void
    {
        header("Location: $route");
        exit;
    }

    protected function debug(string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $logFile = BASE_PATH . '/debug.log';
        $logMessage = "[$timestamp] " . $message . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    /**
     * Trata upload de imagem, converte para WebP e remove anterior.
     * Retorna caminho público (ex: /uploads/avatars/abc.webp) ou null se não houve upload.
     */
    protected function handleImageUpload(string $field, string $targetDir, ?string $oldPublicPath = null): ?string
    {
        if (!isset($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES[$field];
        if ($file['error'] !== UPLOAD_ERR_OK || empty($file['tmp_name'])) {
            throw new \RuntimeException("Falha no upload do arquivo.");
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']) ?: '';
        $allowed = [
            'image/jpeg'          => 'jpg',
            'image/png'           => 'png',
            'image/webp'          => 'webp',
            'image/svg+xml'       => 'svg',
            'image/x-icon'        => 'ico',
            'image/vnd.microsoft.icon' => 'ico',
        ];

        if (!isset($allowed[$mime])) {
            throw new \RuntimeException("Tipo de imagem não suportado.");
        }

        $ext = $allowed[$mime];

        $dir = rtrim($targetDir, '/\\');
        $dirAbsolute = str_starts_with($dir, '/')
            ? BASE_PATH . '/' . ltrim($dir, '/')
            : BASE_PATH . '/' . ltrim($dir, '/');

        if (!is_dir($dirAbsolute)) {
            @mkdir($dirAbsolute, 0777, true);
        }
        if (!is_writable($dirAbsolute)) {
            @chmod($dirAbsolute, 0777);
        }
        if (!is_writable($dirAbsolute)) {
            file_put_contents(BASE_PATH . '/debug.log', '[' . date('Y-m-d H:i:s') . "] Upload erro: diretório não gravável {$dirAbsolute}\n", FILE_APPEND);
            throw new \RuntimeException("Diretório de upload não é gravável.");
        }

        $name = bin2hex(random_bytes(8)) . '.' . $ext;
        $dest = $dirAbsolute . '/' . $name;

        if (!is_uploaded_file($file['tmp_name'])) {
            file_put_contents(BASE_PATH . '/debug.log', '[' . date('Y-m-d H:i:s') . "] Upload erro: tmp não é upload válido {$file['tmp_name']}\n", FILE_APPEND);
            throw new \RuntimeException("Arquivo inválido.");
        }

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            file_put_contents(BASE_PATH . '/debug.log', '[' . date('Y-m-d H:i:s') . "] Upload erro: falha ao mover para {$dest}\n", FILE_APPEND);
            throw new \RuntimeException("Não foi possível mover o upload.");
        }

        // Para SVG/ICO não converter; apenas usa o arquivo
        if (in_array($ext, ['svg', 'ico'], true)) {
            $oldAbsolute = null;
            if ($oldPublicPath) {
                $oldAbsolute = str_starts_with($oldPublicPath, '/')
                    ? BASE_PATH . '/public' . $oldPublicPath
                    : $oldPublicPath;
                if ($oldAbsolute && is_file($oldAbsolute)) {
                    @unlink($oldAbsolute);
                }
            }
            return '/' . ltrim(str_replace(BASE_PATH . '/public', '', $dest), '/');
        }

        $oldAbsolute = null;
        if ($oldPublicPath) {
            $oldAbsolute = str_starts_with($oldPublicPath, '/')
                ? BASE_PATH . '/public' . $oldPublicPath
                : $oldPublicPath;
        }

        $converted = Image::replace($dest, $oldAbsolute);

        // gera caminho público
        $publicPath = '/' . ltrim(str_replace(BASE_PATH . '/public', '', $converted), '/');
        return $publicPath;
    }
}
