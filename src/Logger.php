<?php

declare(strict_types=1);

namespace OnePix\WordPressComponents;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Logger implements LoggerInterface
{
    public function __construct(
        private readonly string $appPrefix
    ) {
    }

    public function emergency(mixed $message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert(mixed $message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical(mixed $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error(mixed $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning(mixed $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice(mixed $message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info(mixed $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug(mixed $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    public function log(mixed $level, mixed $message, array $context = []): void
    {
        if (empty($context['codeSource'])) {
            $backtrace              = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];//phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_debug_backtrace
            $context['codeSource']  = isset($backtrace['class']) ? $backtrace['class'] . '::' : '';
            $context['codeSource'] .= $backtrace['function'] ?? '';
        }

        $data = [
            'source' => "{$this->appPrefix}: {$context['codeSource']}",
            'data'   => $message,
        ];

        error_log(print_r($data, true));//phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
    }
}
