<?php

declare(strict_types=1);

namespace App\Core;

use App\Repositories\AttachmentRepository;
use App\Core\Storage\StorageProviderInterface;
use App\Core\Storage\LocalStorageProvider;
use App\Core\Logger;

class Upload
{
    private const DEFAULT_MAX_SIZE = 5_000_000; // 5MB
    private static ?StorageProviderInterface $storage = null;

    private static function storage(): StorageProviderInterface
    {
        if (!self::$storage) {
            self::$storage = new LocalStorageProvider(BASE_PATH . '/public');
        }
        return self::$storage;
    }

    private static function scanForVirus(string $path): void
    {
        $enabled = getenv('ENABLE_CLAMSCAN') ?: '0';
        if (!in_array(strtolower($enabled), ['1', 'true', 'yes', 'on'], true)) {
            return;
        }

        if (!function_exists('shell_exec') || !function_exists('exec')) {
            return;
        }

        // Opcional: se clamscan existir, use
        $clamscan = trim((string) shell_exec('command -v clamscan'));
        if ($clamscan === '') {
            return;
        }
        $cmd = escapeshellcmd($clamscan) . ' --no-summary ' . escapeshellarg($path);
        exec($cmd, $out, $code);
        if ($code === 1) {
            @unlink($path);
            throw new \RuntimeException('Arquivo rejeitado por vírus.');
        }
        if ($code > 1) {
            throw new \RuntimeException('Falha ao executar antivírus.');
        }
    }

    private static function moveUploaded(string $field, array $allowedMimes, string $targetDir, ?string $oldPublicPath = null, int $maxSize = self::DEFAULT_MAX_SIZE): ?array
    {
        if (!isset($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES[$field];
        if ($file['error'] !== UPLOAD_ERR_OK || empty($file['tmp_name'])) {
            throw new \RuntimeException("Falha no upload do arquivo.");
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            throw new \RuntimeException('Arquivo inválido.');
        }

        if ($file['size'] > $maxSize) {
            Logger::error('Upload excedeu limite', ['field' => $field, 'size' => $file['size'], 'max' => $maxSize]);
            throw new \RuntimeException("Arquivo excede o limite de " . $maxSize . " bytes.");
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']) ?: '';
        if (!isset($allowedMimes[$mime])) {
            Logger::error('Upload MIME não suportado', ['field' => $field, 'mime' => $mime]);
            throw new \RuntimeException("Tipo de arquivo não suportado.");
        }
        $ext = $allowedMimes[$mime];

        // Gera caminho relativo organizado por data
        $datePath = date('Y/m/d');
        $baseDir = trim($targetDir, '/');
        $relativeDir = $baseDir === '' ? $datePath : $baseDir . '/' . $datePath;
        $name = bin2hex(random_bytes(8)) . '.' . $ext;
        $relativePath = $relativeDir . '/' . $name;

        $storage = self::storage();
        $saved = $storage->saveUploadedFile($file['tmp_name'], $relativePath);
        $absolutePath = $saved['absolute'];
        $publicPath = $saved['public'];

        self::scanForVirus($absolutePath);

        $oldAbsolute = null;
        if ($oldPublicPath) {
            $oldAbsolute = str_starts_with($oldPublicPath, '/')
                ? BASE_PATH . '/public' . $oldPublicPath
                : $oldPublicPath;
        }

        return [
            'absolute' => $absolutePath,
            'public' => $publicPath,
            'relative' => $relativePath,
            'name' => $name,
            'mime' => $mime,
            'size' => (int) $file['size'],
            'old'  => $oldAbsolute,
        ];
    }

    private static function sanitizeSvg(string $path): void
    {
        $contents = file_get_contents($path);
        if ($contents === false) {
            throw new \RuntimeException('Não foi possível ler o SVG.');
        }

        // Remove tags <script> e atributos on*
        $contents = preg_replace('#<script[\s\S]*?</script>#i', '', $contents);
        $contents = preg_replace('/on\w+\s*=\s*"[^"]*"/i', '', $contents);
        $contents = preg_replace("/on\w+\s*=\s*'[^']*'/i", '', $contents);
        if ($contents === null) {
            throw new \RuntimeException('Falha ao sanitizar SVG.');
        }

        file_put_contents($path, $contents);
    }

    public static function uploadImage(string $field, string $targetDir, ?string $oldPublicPath = null, int $maxSize = self::DEFAULT_MAX_SIZE, int $quality = 80): ?string
    {
        $data = self::moveUploaded($field, [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
        ], $targetDir, $oldPublicPath, $maxSize);

        if (!$data) {
            return null;
        }

        // SVG: não converte, apenas sanitiza e substitui
        if (str_ends_with(strtolower($data['absolute']), '.svg')) {
            self::sanitizeSvg($data['absolute']);
            if ($data['old'] && $data['old'] !== $data['absolute'] && is_file($data['old'])) {
                @unlink($data['old']);
            }
            $publicPath = $data['public'];
            self::storeAttachmentMeta($publicPath, $data['name'], $data['mime'], $data['size']);
            return $publicPath;
        }

        $converted = Image::replace($data['absolute'], $data['old'], $quality);
        $publicPath = '/' . ltrim(str_replace(BASE_PATH . '/public', '', $converted), '/');
        self::storeAttachmentMeta($publicPath, $data['name'], $data['mime'], $data['size']);
        return $publicPath;
    }

    public static function uploadFile(string $field, string $targetDir, array $allowedMimes, ?string $oldPublicPath = null, int $maxSize = self::DEFAULT_MAX_SIZE): ?string
    {
        $data = self::moveUploaded($field, $allowedMimes, $targetDir, $oldPublicPath, $maxSize);
        if (!$data) {
            return null;
        }

        // Não converte, apenas substitui e remove antigo
        if ($data['old'] && $data['old'] !== $data['absolute'] && is_file($data['old'])) {
            @unlink($data['old']);
        }

        $publicPath = $data['public'];
        self::storeAttachmentMeta($publicPath, $data['name'], $data['mime'], $data['size']);
        return $publicPath;
    }

    public static function uploadDocument(string $field, string $targetDir, ?string $oldPublicPath = null, int $maxSize = self::DEFAULT_MAX_SIZE): ?string
    {
        return self::uploadFile($field, $targetDir, [
            'application/pdf' => 'pdf',
            'text/csv' => 'csv',
            'text/plain' => 'txt',
        ], $oldPublicPath, $maxSize);
    }

    /**
     * Permite trocar o storage provider (ex.: S3 no futuro).
     */
    public static function setStorage(StorageProviderInterface $storage): void
    {
        self::$storage = $storage;
    }

    private static function storeAttachmentMeta(string $publicPath, string $filename, string $mime, int $size): void
    {
        try {
            $repo = new AttachmentRepository();
            $repo->create($publicPath, $filename, $mime, $size);
        } catch (\Throwable $e) {
            error_log('[attachments] Falha ao registrar metadados: ' . $e->getMessage());
        }
    }
}
