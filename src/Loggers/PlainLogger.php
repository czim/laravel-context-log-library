<?php

declare(strict_types=1);

namespace Czim\LaravelContextLogging\Loggers;

use Czim\LaravelContextLogging\Contracts\ContextConfigInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class PlainLogger extends AbstractLogger
{
    protected string $filename;
    protected string $path;


    public function __construct(ContextConfigInterface $config)
    {
        $name = $config->fileName() ?? $config->context();

        $this->filename = $name . '.log';
        $this->path     = $config->path();

        // If we're not using JSON context logging, log to a normal file.
        $path = $this->prepareLogPath();

        $logger = new Logger($name);

        $logger->pushHandler(
            new RotatingFileHandler($path, $config->maxFiles())
        );

        parent::__construct($logger);
    }


    protected function getFileName(): string
    {
        return $this->filename;
    }

    protected function getLogPath(): string
    {
        return $this->path;
    }
}
