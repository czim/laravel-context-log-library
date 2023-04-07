<?php

namespace Czim\LaravelContextLogging\Contracts;

use Czim\LaravelContextLogging\Enums\VerbosityEnum;
use Czim\LaravelContextLogging\Events\AbstractDebugEvent;

interface DebugEventLogPrepperInterface
{
    /**
     * @param VerbosityEnum $verbosity
     * @return DebugEventLogPrepperInterface|$this
     */
    public function verbosity(VerbosityEnum $verbosity): DebugEventLogPrepperInterface;

    public function format(AbstractDebugEvent $event): FormattedForLogInterface;
}
