<?php

namespace Czim\LaravelContextLogging\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface FormattedForLogInterface extends Arrayable
{
    public function getLevel(): string;

    public function getMessage(): string;

    public function getExtra(): array;

    public function toArray(): array;
}
