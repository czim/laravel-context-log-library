[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status](https://travis-ci.org/czim/laravel-context-log-library.svg?branch=master)](https://travis-ci.org/czim/laravel-context-log-library)
[![Coverage Status](https://coveralls.io/repos/github/czim/laravel-context-log-library/badge.svg?branch=master)](https://coveralls.io/github/czim/laravel-context-log-library?branch=master)


# Context Logging library for Laravel

Helper library for standard setup of [czim/laravel-json-context-logging](https://github.com/czim/laravel-json-context-logging).

This helps you to quickly create context channels for logging.
It is not a requirement for using JSON context logging.

## Version Compatibility

| Laravel     | Package |
|:------------|:--------|
| 6.0 - 8.0   | 1.0     |
| 9.0         | 2.0     |
| 10.0 and up | 3.0     |

## Installation

No installation required; however, classes must be bound manually in your service provider.

```php
<?php

use Czim\LaravelContextLogging\Config\StandardJsonContextConfigSource;
use Czim\LaravelContextLogging\Contracts\ContextLoggerFactoryInterface;
use Czim\LaravelContextLogging\Contracts\DebugEventLogPrepperInterface;
use Czim\LaravelContextLogging\Factories\ContextLoggerFactory;

class AppServiceProvider extends \Illuminate\Support\ServiceProvider
{
    // ...

    public function register(): void
    {
        $this->app->singleton(
            DebugEventLogPrepperInterface::class,
            \Your\JsonContextEventLogPrepper::class
        );

        $this->app->singleton(
            ContextLoggerFactoryInterface::class,
            function (): void {
                $factory = new ContextLoggerFactory();
                $factory->setConfigs($this->makeLogContextConfigArray());
                return $factory;
            }
        );
    }

    protected function makeLogContextConfigArray(): array
    {
        return $this->app->make(StandardJsonContextConfigSource::class)
            ->enableContextLogging()
            ->makeConfigArray();
    }
}
```

You will also have to set up your own event, which may extend ` Czim\LaravelContextLogging\Events\AbstractDebugEvent`.
The listener for that event should use the `ContextLoggerFactoryInterface` to make a logger,
and the `DebugEventLogPrepperInterface` to render the event into loggable data to be logged by it.



## Credits

- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/czim/laravel-context-log-library.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/czim/laravel-context-log-library.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/czim/laravel-context-log-library
[link-downloads]: https://packagist.org/packages/czim/laravel-context-log-library
[link-author]: https://github.com/czim
[link-contributors]: ../../contributors
