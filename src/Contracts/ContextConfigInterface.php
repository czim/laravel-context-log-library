<?php

namespace Czim\LaravelContextLogging\Contracts;

interface ContextConfigInterface
{
    /**
     * Whether JSON context logging is enabled.
     * When disabled, plain text logging is used as a fallback.
     *
     * @return bool
     */
    public function enabled(): bool;

    /**
     * Context string key.
     *
     * @return string
     */
    public function context(): string;

    /**
     * The path (without the filename) to log to.
     *
     * @return string|null
     */
    public function path(): ?string;


    /**
     * Filename without extension, for log file.
     *
     * @return string|null
     */
    public function fileName(): ?string;

    /**
     * Maximum when rotating files.
     *
     * @return int
     */
    public function maxFiles(): int;
}
