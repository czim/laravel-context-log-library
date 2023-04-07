<?php

declare(strict_types=1);

namespace Czim\LaravelContextLogging\Loggers;

use Czim\LaravelContextLogging\Contracts\ContextConfigInterface;
use Czim\LaravelJsonContextLogging\Contracts\LoggerFactoryInterface;

class ContextLogger extends AbstractLogger
{
    protected string $filename;
    protected string $path;


    public function __construct(ContextConfigInterface $config)
    {
        /** @var LoggerFactoryInterface $factory */
        $factory = resolve(LoggerFactoryInterface::class);

        $this->filename = ($config->fileName() ?? $config->context()) . '.log';
        $this->path     = $config->path();

        parent::__construct(
            $factory->make($config->context())
        );
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
