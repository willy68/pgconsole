<?php

namespace Application\Console;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;

class ConsoleApplication extends Application
{
    public $container;

    public $config = [];

    public function __construct(
        array $config,
        string $name = 'UNKNOWN',
        string $version = 'UNKNOWN'
    ) {
        parent::__construct($name, $version);
        $this->config = $config;
    }

    /**
     * Get DI container
     *
     * @return ContainerInterface
     * @throws Exception
     */
    public function getContainer(): ContainerInterface
    {
        if ($this->container === null) {
            $builder = new ContainerBuilder();
            $env = getenv('ENV') ?: 'production';
            if ($env === 'production') {
                $builder->enableCompilation('tmp/di');
                $builder->writeProxiesToFile(true, 'tmp/proxies');
            }
            foreach ($this->config as $config) {
                $builder->addDefinitions($config);
            }
            $this->container = $builder->build();
        }
        return $this->container;
    }
}
