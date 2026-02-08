<?php

declare(strict_types=1);

namespace App\Core;

class Logger
{
    private const DEFAULT_CHANNEL = 'app';

    public static function log(string $level, string $message, array $context = [], string $channel = self::DEFAULT_CHANNEL): void
    {
        $date = date('Y-m-d');
        $logDir = BASE_PATH . '/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0775, true);
        }

        $file = "{$logDir}/{$channel}-{$date}.log";
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = '';
        if (!empty($context)) {
            $contextStr = ' ' . json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        $line = "[{$timestamp}] {$level}: {$message}{$contextStr}" . PHP_EOL;
        @file_put_contents($file, $line, FILE_APPEND);
    }

    public static function info(string $message, array $context = []): void
    {
        self::log('INFO', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::log('ERROR', $message, $context);
    }
}
