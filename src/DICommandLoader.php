<?php

namespace Application\Console;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;

class DICommandLoader
{
    public static function getDICommandLoader(ContainerInterface $c, array $commands): CommandLoaderInterface
    {
        return new ContainerCommandLoader($c, $commands);
    }
}
