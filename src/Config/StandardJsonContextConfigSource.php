<?php

declare(strict_types=1);

namespace Czim\LaravelContextLogging\Config;

use Czim\LaravelContextLogging\Contracts\ContextConfigInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class StandardJsonContextConfigSource
{
    protected bool $jsonContextEnabled = true;

    public function enableContextLogging(): StandardJsonContextConfigSource
    {
        $this->jsonContextEnabled = true;

        return $this;
    }

    public function disbableContextLogging(): StandardJsonContextConfigSource
    {
        $this->jsonContextEnabled = false;

        return $this;
    }

    /**
     * @return array<string, ContextConfigInterface> by context string
     */
    public function makeConfigArray(): array
    {
        $channels = Config::get('json-context-logging.channels', []);

        return array_combine(
            array_keys($channels),
            array_map(
                function (array $config, string $key): ContextConfigInterface {
                    $file = Arr::get($config, 'file');

                    if ($file !== null) {
                        $file = pathinfo($file, PATHINFO_FILENAME);
                    }

                    return new ContextConfig(
                        $key,
                        $this->getBaseLogPath(),
                        $file,
                        $this->jsonContextEnabled,
                        $this->getRotatingMaxFiles()
                    );
                },
                $channels,
                array_keys($channels),
            )
        );
    }

    protected function getBaseLogPath(): string
    {
        return Config::get('json-context-logging.default.path', $this->getDefaultContextLogStoragePath());
    }

    protected function getRotatingMaxFiles(): int
    {
        return Config::get('json-context-logging.default.handler.parameters.max_files', $this->getDefaultMaxRotatingFiles());
    }

    protected function getDefaultContextLogStoragePath(): string
    {
        return App::storagePath('logs/context');
    }

    protected function getDefaultMaxRotatingFiles(): int
    {
        return 21;
    }
}
