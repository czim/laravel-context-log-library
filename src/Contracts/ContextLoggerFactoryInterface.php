<?php

namespace Czim\LaravelContextLogging\Contracts;

use Psr\Log\LoggerInterface;

interface ContextLoggerFactoryInterface
{
    /**
     * @param ContextConfigInterface[] $configs     keyed by context string
     */
    public function setConfigs(array $configs): void;

    public function make(string $context): LoggerInterface;
}
