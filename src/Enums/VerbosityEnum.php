<?php

namespace Czim\LaravelContextLogging\Enums;

enum VerbosityEnum: int
{
    case NOT_VERBOSE       = 0;
    case VERBOSE           = 1;
    case VERY_VERBOSE      = 2;
    case VERY_VERY_VERBOSE = 3;
}
