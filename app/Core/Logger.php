<?php

declare(strict_types=1);

namespace App\Core;

class Logger
{
    public static function write(string $level, string $message, array $context = []): void
    {
        $file = STORAGE_PATH . '/logs/app-' . date('Y-m-d') . '.log';
        $line = sprintf(
            "[%s] %s: %s %s%s",
            date('Y-m-d H:i:s'),
            strtoupper($level),
            $message,
            $context ? json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '',
            PHP_EOL
        );
        file_put_contents($file, $line, FILE_APPEND);
    }

    public static function error(string $message, array $context = []): void
    {
        self::write('error', $message, $context);
    }

    public static function info(string $message, array $context = []): void
    {
        self::write('info', $message, $context);
    }
}
