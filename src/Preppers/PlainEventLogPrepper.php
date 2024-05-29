<?php

declare(strict_types=1);

namespace Czim\LaravelContextLogging\Preppers;

use Czim\LaravelContextLogging\Contracts\DebugEventLogPrepperInterface;
use Czim\LaravelContextLogging\Contracts\FormattedForLogInterface;
use Czim\LaravelContextLogging\Data\FormattedForLog;
use Czim\LaravelContextLogging\Enums\LogLevelEnum;
use Czim\LaravelContextLogging\Enums\VerbosityEnum;
use Czim\LaravelContextLogging\Events\AbstractDebugEvent;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Throwable;

/**
 * For preparing, as well as possible given the context, a debug event for logging as a PSR log write.
 */
class PlainEventLogPrepper implements DebugEventLogPrepperInterface
{
    protected const MAX_TRACE_LINE_LENGTH   = 20;
    protected const MAX_TRACE_STRING_LENGTH = 2000;

    protected LogLevelEnum $level = LogLevelEnum::DEBUG;
    protected string $message = '';

    /**
     * @var array<string, mixed>
     */
    protected array $extra = [];

    protected AbstractDebugEvent $event;
    protected VerbosityEnum $verbosity;


    public function __construct()
    {
        $this->resetFluent();
    }


    public function verbosity(VerbosityEnum $verbosity): DebugEventLogPrepperInterface
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

        return new FormattedForLog($level->value, $message, $extra);
    }


    protected function addGeneralSessionContext(): void
    {
        $this->message = $this->getCategoryAsPrefix() . $this->message;
    }

    protected function addSessionInformationToExtra(): void
    {
        $data = [];

        if (App::runningInConsole()) {
            $data['console'] = true;
        } elseif ($this->isVerbose()) {
            $data['sessionId'] = Session::getId();
            $data['ip']        = Request::ip();
        }

        if (! count($data)) {
            return;
        }

        $this->extra['session'] = $data;
    }

    protected function addExceptionInformationToExtra(): void
    {
        $exception = $this->event->getException();

        if (! $exception) {
            return;
        }

        $data = [
            'class'   => get_class($exception),
            'message' => $exception->getMessage(),
            'code'    => $exception->getCode(),
            'file'    => $exception->getFile() . ':' . $exception->getLine(),
        ];

        if ($this->isVerbose()) {
            $data['trace'] = $this->getLimitStackTraceAsString($exception);
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

    protected function getLimitStackTraceAsString(Throwable $exception): string
    {
        $traceLines = explode("\n", $exception->getTraceAsString());
        $traceLines = array_slice($traceLines, 0, static::MAX_TRACE_LINE_LENGTH);

        $traceString = implode("\n", $traceLines);

        return Str::limit($traceString, static::MAX_TRACE_STRING_LENGTH);
    }

    protected function getCategoryAsPrefix(): string
    {
        if (! $this->event->getCategory()) {
            return '';
        }

        return "[{$this->event->getCategory()}] ";
    }

    protected function isVerbose(): bool
    {
        return $this->verbosity->value >= VerbosityEnum::VERBOSE->value;
    }

    protected function isVeryVerbose(): bool
    {
        return $this->verbosity->value > VerbosityEnum::VERBOSE->value;
    }

    protected function resetFluent(): void
    {
        unset($this->event);

        $this->verbosity = $this->getDefaultVerbosity();
        $this->level     = LogLevelEnum::DEBUG;
        $this->message   = '';
        $this->extra     = [];
    }

    protected function getDefaultVerbosity(): VerbosityEnum
    {
        return VerbosityEnum::VERBOSE;
    }
}
