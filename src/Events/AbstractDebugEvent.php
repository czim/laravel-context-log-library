<?php

namespace Czim\LaravelContextLogging\Events;

use Czim\LaravelContextLogging\Enums\LogLevelEnum;
use Illuminate\Support\Arr;
use Throwable;
use UnexpectedValueException;

abstract class AbstractDebugEvent
{
    /**
     * @var string|null
     */
    protected $channel;

    /**
     * @var string
     */
    protected $logLevel = LogLevelEnum::INFO;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var array
     */
    protected $extra;

    /**
     * @var string|null
     */
    protected $category;

    /**
     * @var Throwable|null
     */
    protected $exception;

    /**
     * Whether we should log this with high verbosity.
     *
     * @var bool
     */
    protected $verbose = false;

    /**
     * Whether we should log this with less than default verbosity.
     *
     * @var bool
     */
    protected $notVerbose = false;


    public function __construct(string $message, array $extra = [])
    {
        $this->message = $message;
        $this->extra   = $extra;
    }


    public static function make(string $message, array $extra = []): AbstractDebugEvent
    {
        return new static($message, $extra);
    }


    public function fire(): void
    {
        event($this);
    }

    /**
     * @param string     $key       if array, overwrites current extra data
     * @param mixed|null $value
     * @return AbstractDebugEvent|$this
     */
    public function extra(string $key, $value = null): AbstractDebugEvent
    {
        Arr::set($this->extra, $key, $value);

        return $this;
    }

    /**
     * @param array $extra
     * @return AbstractDebugEvent|$this
     */
    public function extraArray(array $extra): AbstractDebugEvent
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * @param string $channel
     * @return AbstractDebugEvent|$this
     */
    public function channel(string $channel): AbstractDebugEvent
    {
        if ( ! $this->isValidChannel($channel)) {
            throw new UnexpectedValueException("Invalid debug event channel: '{$channel}'");
        }

        $this->channel = $channel;

        return $this;
    }

    /**
     * @param string|null $name
     * @return AbstractDebugEvent|$this
     */
    public function category(?string $name): AbstractDebugEvent
    {
        $this->category = $name;

        return $this;
    }

    /**
     * @param Throwable $exception
     * @return AbstractDebugEvent|$this
     */
    public function exception(Throwable $exception): AbstractDebugEvent
    {
        $this->exception = $exception;

        return $this;
    }

    /**
     * @return AbstractDebugEvent|$this
     */
    public function verbose(): AbstractDebugEvent
    {
        $this->notVerbose = false;
        $this->verbose    = true;

        return $this;
    }

    /**
     * @return AbstractDebugEvent|$this
     */
    public function notVerbose(): AbstractDebugEvent
    {
        $this->verbose    = false;
        $this->notVerbose = true;

        return $this;
    }

    /**
     * @return AbstractDebugEvent|$this
     */
    public function critical(): AbstractDebugEvent
    {
        $this->logLevel = LogLevelEnum::CRITICAL;

        return $this;
    }

    /**
     * @return AbstractDebugEvent|$this
     */
    public function alert(): AbstractDebugEvent
    {
        $this->logLevel = LogLevelEnum::ALERT;

        return $this;
    }

    /**
     * @return AbstractDebugEvent|$this
     */
    public function error(): AbstractDebugEvent
    {
        $this->logLevel = LogLevelEnum::ERROR;

        return $this;
    }

    /**
     * @return AbstractDebugEvent|$this
     */
    public function warning(): AbstractDebugEvent
    {
        $this->logLevel = LogLevelEnum::WARNING;

        return $this;
    }

    /**
     * @return AbstractDebugEvent|$this
     */
    public function notice(): AbstractDebugEvent
    {
        $this->logLevel = LogLevelEnum::NOTICE;

        return $this;
    }

    /**
     * @return AbstractDebugEvent|$this
     */
    public function info(): AbstractDebugEvent
    {
        $this->logLevel = LogLevelEnum::INFO;

        return $this;
    }

    /**
     * @return AbstractDebugEvent|$this
     */
    public function debug(): AbstractDebugEvent
    {
        $this->logLevel = LogLevelEnum::DEBUG;

        return $this;
    }


    public function getChannel(): string
    {
        return $this->channel ?? $this->getDefaultChannel();
    }

    public function getLogLevel(): string
    {
        return $this->logLevel;
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
     * @return AbstractDebugEvent|$this
     */
    public function when(?bool $value, callable $callback): AbstractDebugEvent
    {
        if ($value) {
            call_user_func($callback, $this);
        }

        return $this;
    }


    abstract protected function isValidChannel(string $channel): bool;

    abstract protected function getDefaultChannel(): string;
}
