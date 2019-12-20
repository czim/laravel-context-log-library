<?php

namespace Czim\LaravelContextLogging\Data;

use Czim\LaravelContextLogging\Contracts\FormattedForLogInterface;

class FormattedForLog implements FormattedForLogInterface
{
    /**
     * @var string
     */
    protected $level;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var array
     */
    protected $extra;


    public function __construct(string $level, string $message, array $extra)
    {
        $this->level   = $level;
        $this->message = $message;
        $this->extra   = $extra;
    }


    public function getLevel(): string
    {
        return $this->level;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getExtra(): array
    {
        return $this->extra;
    }

    public function toArray(): array
    {
        return [
            $this->getLevel(),
            $this->getMessage(),
            $this->getExtra(),
        ];
    }
}
