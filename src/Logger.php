<?php

declare(strict_types=1);

namespace OnePix\WordPressComponents;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Logger implements LoggerInterface
{
    public function __construct(
        private readonly string $plugin_id
    ) {
    }

    public function emergency($message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical($message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error($message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning($message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice($message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug($message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * @param $level
     * @param $message
     *
     */
    public function log($level, $message, array $context = []): void
    {
        if (empty($context['codeSource'])) {
            $backtrace              = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];
            $context['codeSource']  = isset($backtrace['class']) ? $backtrace['class'] . '::' : '';
            $context['codeSource'] .= $backtrace['function'] ?? '';
        }

        $data = [
            'source' => "{$this->plugin_id}: {$context['codeSource']}",
            'data'   => $message,
        ];

        error_log(print_r($data, true));
    }
}
