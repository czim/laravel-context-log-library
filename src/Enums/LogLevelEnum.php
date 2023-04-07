<?php

declare(strict_types=1);

namespace Czim\LaravelContextLogging\Enums;

enum LogLevelEnum: string
{
    case DEBUG     = 'debug';
    case INFO      = 'info';
    case NOTICE    = 'notice';
    case WARNING   = 'warning';
    case ERROR     = 'error';
    case CRITICAL  = 'critical';
    case ALERT     = 'alert';
    case EMERGENCY = 'emergency';
}
