<?php

declare(strict_types=1);

namespace App\Core\Storage;

class LocalStorageProvider implements StorageProviderInterface
{
    public function __construct(private string $publicRoot)
    {
    }

    public function saveUploadedFile(string $tmpPath, string $relativePath): array
    {
        $relativePath = ltrim($relativePath, '/');
        $destAbsolute = rtrim($this->publicRoot, '/\\') . '/' . $relativePath;

        $dir = dirname($destAbsolute);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        if (!move_uploaded_file($tmpPath, $destAbsolute)) {
            throw new \RuntimeException("Não foi possível mover o upload.");
        }

        $publicPath = '/' . ltrim(str_replace($this->publicRoot, '', $destAbsolute), '/');

        return [
            'absolute' => $destAbsolute,
            'public' => $publicPath,
        ];
    }
}
