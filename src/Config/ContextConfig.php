<?php

declare(strict_types=1);

namespace Czim\LaravelContextLogging\Config;

use Czim\LaravelContextLogging\Contracts\ContextConfigInterface;
use Illuminate\Support\Facades\App;

class ContextConfig implements ContextConfigInterface
{
    public function __construct(
        protected readonly string $context,
        protected readonly ?string $path = null,
        protected readonly ?string $fileName = null,
        protected readonly bool $enabled = true,
        protected readonly int $maxFiles = 14,
    ) {
    }


    /**
     * {@inheritDoc}
     */
    public function enabled(): bool
    {
        return $this->enabled;
    }

    /**
     * {@inheritDoc}
     */
    public function context(): string
    {
        return $this->context;
    }

    /**
     * {@inheritDoc}
     */
    public function fileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * {@inheritDoc}
     */
    public function maxFiles(): int
    {
        return $this->maxFiles;
    }

    public function path(): string
    {
        if ($this->path === null) {
            return App::storagePath('logs/context');
        }

        return $this->path;
    }
}
