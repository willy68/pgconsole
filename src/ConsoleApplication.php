<?php

namespace Application\Console;

use Symfony\Component\Console\Application;

class ConsoleApplication extends Application
{
    public $container;

    public $config = [];

    public $basePath;

    public function __construct(
        array $config,
        string $basePath = __DIR__,
        string $name = 'PgConsole',
        string $version = '0.0.1'
    ) {
        parent::__construct($name, $version);
        $this->config = $config;
        $this->basePath = $basePath;
    }

    /**
     * Return base path
     *
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }
}
