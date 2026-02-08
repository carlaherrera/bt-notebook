<?php

declare(strict_types=1);

namespace App\Core\Storage;

interface StorageProviderInterface
{
    /**
     * Salva um arquivo enviado (tmp) em um caminho relativo de storage.
     * Deve retornar caminhos absoluto e público.
     *
     * @return array{absolute:string, public:string}
     */
    public function saveUploadedFile(string $tmpPath, string $relativePath): array;
}
