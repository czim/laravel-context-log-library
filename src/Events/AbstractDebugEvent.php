<?php

declare(strict_types=1);

namespace Czim\LaravelContextLogging\Events;

use Czim\LaravelContextLogging\Enums\LogLevelEnum;
use Illuminate\Support\Arr;
use Throwable;
use UnexpectedValueException;

abstract class AbstractDebugEvent
{
    protected ?string $channel = null;
    protected LogLevelEnum $logLevel = LogLevelEnum::INFO;
    protected string $message;

    /**
     * @var array<string, mixed>
     */
    protected array $extra;

    protected ?string $category = null;
    protected ?Throwable $exception = null;

    /**
     * Whether we should log this with high verbosity.
     *
     * @var bool
     */
    protected bool $verbose = false;

    /**
     * Whether we should log this with less than default verbosity.
     *
     * @var bool
     */
    protected bool $notVerbose = false;


    /**
     * @param string               $message
     * @param array<string, mixed> $extra
     */
    public function __construct(string $message, array $extra = [])
    {
        $this->message = $message;
        $this->extra   = $extra;
    }

    /**
     * @param string               $message
     * @param array<string, mixed> $extra
     * @return AbstractDebugEvent
     */
    public static function make(string $message, array $extra = []): static
    {
        return new static($message, $extra);
    }


    public function fire(): void
    {
        event($this);
    }

    /**
     * @param string     $key   if array, overwrites current extra data
     * @param mixed|null $value
     * @return static|$this
     */
    public function extra(string $key, mixed $value = null): static
    {
        Arr::set($this->extra, $key, $value);

        return $this;
    }

    /**
     * @param array<string, mixed> $extra
     * @return static|$this
     */
    public function extraArray(array $extra): static
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * @param string $channel
     * @return static|$this
     */
    public function channel(string $channel): static
    {
        if ( ! $this->isValidChannel($channel)) {
            throw new UnexpectedValueException("Invalid debug event channel: '{$channel}'");
        }

        $this->channel = $channel;

        return $this;
    }

    /**
     * @param string|null $name
     * @return static|$this
     */
    public function category(?string $name): static
    {
        $this->category = $name;

        return $this;
    }

    /**
     * @param Throwable $exception
     * @return static|$this
     */
    public function exception(Throwable $exception): static
    {
        $this->exception = $exception;

        return $this;
    }

    /**
     * @return static|$this
     */
    public function verbose(): static
    {
        $this->notVerbose = false;
        $this->verbose    = true;

        return $this;
    }

    /**
     * @return static|$this
     */
    public function notVerbose(): static
    {
        $this->verbose    = false;
        $this->notVerbose = true;

        return $this;
    }

    /**
     * @return static|$this
     */
    public function critical(): static
    {
        $this->logLevel = LogLevelEnum::CRITICAL;

        return $this;
    }

    /**
     * @return static|$this
     */
    public function alert(): static
    {
        $this->logLevel = LogLevelEnum::ALERT;

        return $this;
    }

    /**
     * @return static|$this
     */
    public function error(): static
    {
        $this->logLevel = LogLevelEnum::ERROR;

        return $this;
    }

    /**
     * @return static|$this
     */
    public function warning(): static
    {
        $this->logLevel = LogLevelEnum::WARNING;

        return $this;
    }

    /**
     * @return static|$this
     */
    public function notice(): static
    {
        $this->logLevel = LogLevelEnum::NOTICE;

        return $this;
    }

    /**
     * @return static|$this
     */
    public function info(): static
    {
        $this->logLevel = LogLevelEnum::INFO;

        return $this;
    }

    /**
     * @return static|$this
     */
    public function debug(): static
    {
        $this->logLevel = LogLevelEnum::DEBUG;

        return $this;
    }


    public function getChannel(): string
    {
        return $this->channel ?? $this->getDefaultChannel();
    }

    public function getLogLevel(): LogLevelEnum
    {
        return $this->logLevel;
    }

    public function getLogLevelAsString(): string
    {
        return $this->logLevel->value;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getExtra(): array
    {
        return $this->extra;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function getException(): ?Throwable
    {
        return $this->exception;
    }

    public function isVerbose(): bool
    {
        return $this->verbose;
    }

    public function isNotVerbose(): bool
    {
        return $this->notVerbose;
    }

    /**
     * Apply the callback's effects if the given "value" is true.
     *
     * @param bool|null $value
     * @param callable  $callback
     * @return static|$this
     */
    public function when(?bool $value, callable $callback): static
    {
        if ($value) {
            call_user_func($callback, $this);
        }

        return $this;
    }


    abstract protected function isValidChannel(string $channel): bool;

    abstract protected function getDefaultChannel(): string;
}
