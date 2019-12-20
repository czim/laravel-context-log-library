<?php

namespace Czim\LaravelContextLogging\Config;

use Czim\LaravelContextLogging\Contracts\ContextConfigInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class StandardJsonContextConfigSource
{
    /**
     * @var bool
     */
    protected $jsonContextEnabled = true;


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
     * @return ContextConfigInterface[]     keyed by context string
     */
    public function makeConfigArray(): array
    {
        $channels = Config::get('json-context-logging.channels', []);

        return array_combine(
            array_keys($channels),
            array_map(
                function (array $config, string $key) {

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
                array_keys($channels)
            )
        );
    }

    protected function getBaseLogPath(): string
    {
        return Config::get('json-context-logging.default.path', App::storagePath('logs/context'));
    }

    protected function getRotatingMaxFiles(): int
    {
        return Config::get('json-context-logging.default.handler.parameters.max_files', 21);
    }
}
