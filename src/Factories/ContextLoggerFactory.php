<?php

namespace Czim\LaravelContextLogging\Factories;

use Czim\LaravelContextLogging\Contracts\ContextConfigInterface;
use Czim\LaravelContextLogging\Contracts\ContextLoggerFactoryInterface;
use Czim\LaravelContextLogging\Loggers\ContextLogger;
use Czim\LaravelContextLogging\Loggers\PlainLogger;
use Psr\Log\LoggerInterface;
use UnexpectedValueException;

class ContextLoggerFactory implements ContextLoggerFactoryInterface
{
    /**
     * @var ContextConfigInterface[]
     */
    protected $configs;


    /**
     * @param ContextConfigInterface[] $configs     keyed by context string
     */
    public function setConfigs(array $configs): void
    {
        $this->configs = $configs;
    }

    public function make(string $context): LoggerInterface
    {
        $config = $this->getConfig($context);

        if ( ! $config->enabled()) {
            return $this->makePlainLogger($config);
        }

        return $this->makeContextLogger($config);
    }


    protected function makePlainLogger(ContextConfigInterface $config): LoggerInterface
    {
        return new PlainLogger($config);
    }

    protected function makeContextLogger(ContextConfigInterface $config): LoggerInterface
    {
        return new ContextLogger($config);
    }

    protected function getConfig(string $context): ContextConfigInterface
    {
        if ( ! array_key_exists($context, $this->configs)) {
            throw new UnexpectedValueException("No context log config available for context: '{$context}'");
        }

        return $this->configs[$context];
    }
}
