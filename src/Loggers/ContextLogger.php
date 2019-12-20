<?php

namespace Czim\LaravelContextLogging\Loggers;

use Czim\LaravelContextLogging\Contracts\ContextConfigInterface;
use Czim\LaravelJsonContextLogging\Contracts\LoggerFactoryInterface;

class ContextLogger extends AbstractLogger
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * @var string
     */
    protected $path;


    public function __construct(ContextConfigInterface $config)
    {
        /** @var LoggerFactoryInterface $factory */
        $factory = app(LoggerFactoryInterface::class);

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
