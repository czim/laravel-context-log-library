<?php

namespace Czim\LaravelContextLogging\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static LogLevelEnum DEBUG()
 * @method static LogLevelEnum INFO()
 * @method static LogLevelEnum NOTICE()
 * @method static LogLevelEnum WARNING()
 * @method static LogLevelEnum ERROR()
 * @method static LogLevelEnum CRITICAL()
 * @method static LogLevelEnum ALERT()
 * @method static LogLevelEnum EMERGENCY()
 */
class LogLevelEnum extends Enum
{
    const DEBUG     = 'debug';
    const INFO      = 'info';
    const NOTICE    = 'notice';
    const WARNING   = 'warning';
    const ERROR     = 'error';
    const CRITICAL  = 'critical';
    const ALERT     = 'alert';
    const EMERGENCY = 'emergency';
}
