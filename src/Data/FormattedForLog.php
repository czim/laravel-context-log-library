<?php

declare(strict_types=1);

namespace Czim\LaravelContextLogging\Data;

use Czim\LaravelContextLogging\Contracts\FormattedForLogInterface;

class FormattedForLog implements FormattedForLogInterface
{
    public function __construct(
        protected readonly string $level,
        protected readonly string $message,
        protected readonly array $extra,
    ) {
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array<string, mixed>
     */
    public function getExtra(): array
    {
        return $this->extra;
    }

    /**
     * @return array{0: string, 1: string, 2: array<string, mixed>}
     */
    public function toArray(): array
    {
        return [
            $this->getLevel(),
            $this->getMessage(),
            $this->getExtra(),
        ];
    }
}
