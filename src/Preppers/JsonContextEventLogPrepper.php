<?php

namespace Czim\LaravelContextLogging\Preppers;

use Czim\LaravelContextLogging\Contracts\FormattedForLogInterface;
use Czim\LaravelContextLogging\Data\FormattedForLog;
use Czim\LaravelContextLogging\Enums\VerbosityEnum;
use Czim\LaravelContextLogging\Events\AbstractDebugEvent;

/**
 * For JSON context logging, which is intended to be sent to Elasticsearch,
 * we should be always be very verbose, and use the extra data, rather than
 * prefix the message, where possible.
 */
class JsonContextEventLogPrepper extends PlainEventLogPrepper
{
    /**
     * Extra data to be deeper nested under the 'data' key.
     *
     * @var array
     */
    protected $customExtra = [];


    public function format(AbstractDebugEvent $event): FormattedForLogInterface
    {
        $this->event = $event;

        $this->level       = $this->event->getLogLevel();
        $this->message     = $this->event->getMessage();
        $this->customExtra = $this->event->getExtra();

        $this->addGeneralSessionContext();
        $this->addSessionInformationToExtra();
        $this->addExceptionInformationToExtra();

        $this->decorateExtra();

        if (count($this->customExtra)) {
            $this->extra['data'] = $this->customExtra;
        }

        $level   = $this->level;
        $message = $this->message;
        $extra   = $this->extra;

        $this->resetFluent();

        return new FormattedForLog($level, $message, $extra);
    }

    /**
     * Hookable method to further decorate the extra data array.
     */
    protected function decorateExtra(): void
    {
    }


    protected function addGeneralSessionContext(): void
    {
        if ( ! $this->event->getCategory()) {
            return;
        }

        $this->extra['category'] = $this->event->getCategory();
    }

    protected function isVerbose(): bool
    {
        return true;
    }

    protected function isVeryVerbose(): bool
    {
        return true;
    }

    protected function getDefaultVerbosity(): int
    {
        return VerbosityEnum::VERY_VERBOSE;
    }
}
