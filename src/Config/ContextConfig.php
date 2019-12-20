<?php

namespace Czim\LaravelContextLogging\Config;

use Czim\LaravelContextLogging\Contracts\ContextConfigInterface;

class ContextConfig implements ContextConfigInterface
{
    /**
     * @var string
     */
    protected $context;

    /**
     * @var string|null
     */
    protected $path;

    /**
     * @var null
     */
    protected $fileName;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var int
     */
    protected $maxFiles;


    public function __construct(
        string $context,
        ?string $path = null,
        ?string $fileName = null,
        bool $enabled = true,
        $maxFiles = 14
    ) {
        $this->context  = $context;
        $this->path     = $path;
        $this->fileName = $fileName;
        $this->enabled  = $enabled;
        $this->maxFiles = $maxFiles;
    }


    /**
     * @inheritDoc
     */
    public function enabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @inheritDoc
     */
    public function context(): string
    {
        return $this->context;
    }

    /**
     * @inheritDoc
     */
    public function fileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @inheritDoc
     */
    public function maxFiles(): int
    {
        return $this->maxFiles;
    }

    public function path(): string
    {
        if ($this->path === null) {
            return storage_path('logs/context');
        }

        return $this->path;
    }
}
