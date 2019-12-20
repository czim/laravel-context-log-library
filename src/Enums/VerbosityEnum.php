<?php

namespace Czim\LaravelContextLogging\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static VerbosityEnum NOT_VERBOSE()
 * @method static VerbosityEnum VERBOSE()
 * @method static VerbosityEnum VERY_VERBOSE()
 * @method static VerbosityEnum VERY_VERY_VERBOSE()
 */
class VerbosityEnum extends Enum
{
    const NOT_VERBOSE       = 0;
    const VERBOSE           = 1;
    const VERY_VERBOSE      = 2;
    const VERY_VERY_VERBOSE = 3;
}
