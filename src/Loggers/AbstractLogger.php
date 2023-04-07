<?php

declare(strict_types=1);

namespace Czim\LaravelContextLogging\Loggers;

use Illuminate\Support\Facades\File;
use Psr\Log\LoggerInterface;
use Stringable;

abstract class AbstractLogger implements LoggerInterface
{
    public function __construct(protected LoggerInterface $logger)
    {
    }


    /**
     * {@inheritDoc}
     */
    public function emergency(string|Stringable $message, array $context = []): void
    {
        $this->logger->emergency($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function alert(string|Stringable $message, array $context = []): void
    {
        $this->logger->alert($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function critical(string|Stringable $message, array $context = []): void
    {
        $this->logger->critical($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function error(string|Stringable $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function warning(string|Stringable $message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function notice(string|Stringable $message, array $context = []): void
    {
        $this->logger->notice($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function info(string|Stringable $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function debug(string|Stringable $message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        $this->logger->log($level, $message, $context);
    }

    protected function getDirectory(): string
    {
        return $this->getLogPath();
    }

    protected function prepareLogPath(): string
    {
        $directory = $this->getDirectory();
        $path      = rtrim($directory, '/') . '/' . $this->getFileName();

        $this->ensureDirectoryExists($directory);

        return $path;
    }

    protected function ensureDirectoryExists(string $directory): void
    {
        if (File::isDirectory($directory)) {
            return;
        }

        File::makeDirectory($directory, $mode = 0777, true, true);
    }

    abstract protected function getFileName(): string;

    abstract protected function getLogPath(): string;
}
