<?php

namespace Application\Console;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;

class DICommandLoader
{
    public static function getDICommandLoader(ContainerInterface $c): CommandLoaderInterface
    {
        return new ContainerCommandLoader($c, [
            'controller' => ControllerCommand::class,
            'controller:all' => ControllersCommand::class,
            'model' => ModelCommand::class,
            'model:all' => ModelsCommand::class
        ]);
    }
}
