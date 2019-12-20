<?php

namespace Czim\LaravelContextLogging\Contracts;

use Czim\LaravelContextLogging\Enums\VerbosityEnum;
use Czim\LaravelContextLogging\Events\AbstractDebugEvent;

interface DebugEventLogPrepperInterface
{
    /**
     * @param int $verbosity
     * @return DebugEventLogPrepperInterface
     * @see VerbosityEnum
     */
    public function verbosity(int $verbosity): DebugEventLogPrepperInterface;

    public function format(AbstractDebugEvent $event): FormattedForLogInterface;
}
