<?php

namespace Czim\LaravelContextLogging\Preppers;

use Czim\LaravelContextLogging\Contracts\DebugEventLogPrepperInterface;
use Czim\LaravelContextLogging\Contracts\FormattedForLogInterface;
use Czim\LaravelContextLogging\Data\FormattedForLog;
use Czim\LaravelContextLogging\Enums\LogLevelEnum;
use Czim\LaravelContextLogging\Enums\VerbosityEnum;
use Czim\LaravelContextLogging\Events\AbstractDebugEvent;

/**
 * For preparing, as best as possible given the context, a debug event for logging as a PSR log write.
 */
class PlainEventLogPrepper implements DebugEventLogPrepperInterface
{
    /**
     * @var string
     */
    protected $level = LogLevelEnum::DEBUG;

    /**
     * @var string
     */
    protected $message = '';

    /**
     * @var array
     */
    protected $extra = [];


    /**
     * @var AbstractDebugEvent
     */
    protected $event;

    /**
     * @var int
     * @see VerbosityEnum
     */
    protected $verbosity;


    public function __construct()
    {
        $this->resetFluent();
    }


    public function verbosity(int $verbosity): DebugEventLogPrepperInterface
    {
        $this->verbosity = $verbosity;

        return $this;
    }

    public function format(AbstractDebugEvent $event): FormattedForLogInterface
    {
        $this->event = $event;

        $this->level   = $this->event->getLogLevel();
        $this->message = $this->event->getMessage();
        $this->extra   = $this->event->getExtra();

        $this->addGeneralSessionContext();
        $this->addSessionInformationToExtra();
        $this->addExceptionInformationToExtra();

        $level   = $this->level;
        $message = $this->message;
        $extra   = $this->extra;

        $this->resetFluent();

        return new FormattedForLog($level, $message, $extra);
    }

    protected function addGeneralSessionContext(): void
    {
        $this->message = $this->getCategoryAsPrefix() . $this->message;
    }

    protected function addSessionInformationToExtra(): void
    {
        $data = [];

        if (app()->runningInConsole()) {
            $data['console'] = true;
        } elseif ($this->isVerbose()) {
            $data['sessionId'] = session()->getId();
            $data['ip']        = request()->ip();
        }

        if ( ! count($data)) {
            return;
        }

        $this->extra['session'] = $data;
    }

    protected function addExceptionInformationToExtra(): void
    {
        $exception = $this->event->getException();

        if ( ! $exception) {
            return;
        }

        $data = [
            'class'   => get_class($exception),
            'message' => $exception->getMessage(),
            'code'    => $exception->getCode(),
            'file'    => $exception->getFile() . ':' . $exception->getLine(),
        ];

        if ($this->isVerbose()) {
            $data['trace'] = $exception->getTraceAsString();
        }

        if ($this->isVeryVerbose() && $exception->getPrevious()) {
            $data['previous'] = [
                'class'   => get_class($exception->getPrevious()),
                'message' => $exception->getPrevious()->getMessage(),
                'code'    => $exception->getPrevious()->getCode(),
                'file'    => $exception->getPrevious()->getFile() . ':' . $exception->getPrevious()->getLine(),
            ];
        }

        $this->extra['exception'] = $data;
    }

    protected function getCategoryAsPrefix(): string
    {
        if ( ! $this->event->getCategory()) {
            return '';
        }

        return "[{$this->event->getCategory()}] ";
    }

    protected function isVerbose(): bool
    {
        return $this->verbosity >= VerbosityEnum::VERBOSE;
    }

    protected function isVeryVerbose(): bool
    {
        return $this->verbosity > VerbosityEnum::VERBOSE;
    }

    protected function resetFluent(): void
    {
        $this->event     = null;
        $this->verbosity = $this->getDefaultVerbosity();
        $this->level     = LogLevelEnum::DEBUG;
        $this->message   = '';
        $this->extra     = [];
    }

    protected function getDefaultVerbosity(): int
    {
        return VerbosityEnum::VERBOSE;
    }
}
