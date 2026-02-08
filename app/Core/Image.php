<?php

declare(strict_types=1);

namespace App\Core;

class Image
{
    private const SUPPORTED = ['jpg', 'jpeg', 'png'];

    /**
     * Converte JPG/PNG para WebP, remove original e retorna caminho do novo arquivo.
     */
    public static function convertToWebp(string $absolutePath, int $quality = 80): string
    {
        $ext = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));
        if (!in_array($ext, self::SUPPORTED, true)) {
            return $absolutePath;
        }

        if (!is_file($absolutePath)) {
            throw new \RuntimeException("Arquivo não encontrado: {$absolutePath}");
        }

        $image = match ($ext) {
            'jpg', 'jpeg' => imagecreatefromjpeg($absolutePath),
            'png' => imagecreatefrompng($absolutePath),
            default => null,
        };

        if (!$image) {
            throw new \RuntimeException("Não foi possível abrir imagem: {$absolutePath}");
        }

        $newPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $absolutePath);
        if ($newPath === null) {
            throw new \RuntimeException("Falha ao gerar nome WebP para {$absolutePath}");
        }

        if (!imagewebp($image, $newPath, $quality)) {
            throw new \RuntimeException("Falha ao converter para WebP: {$absolutePath}");
        }

        // Remove original
        @unlink($absolutePath);

        return $newPath;
    }

    /**
     * Substitui imagem: converte nova para WebP e apaga anterior se diferente.
     */
    public static function replace(string $newAbsolutePath, ?string $oldAbsolutePath = null, int $quality = 80): string
    {
        $convertedPath = self::convertToWebp($newAbsolutePath, $quality);

        if ($oldAbsolutePath && $oldAbsolutePath !== $convertedPath && is_file($oldAbsolutePath)) {
            @unlink($oldAbsolutePath);
        }

        return $convertedPath;
    }
}
