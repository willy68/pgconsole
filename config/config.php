<?php

use Application\Console\DumpEnvCommand;
use Application\Console\GenerateAppKeyCommand;
use Application\Console\DropDatabaseDoctrineCommand;
use Application\Console\CreateDatabaseDoctrineCommand;

return [
    "console.commands" => \DI\add([
        'doctrine:database:create' => CreateDatabaseDoctrineCommand::class,
        'doctrine:database:drop' => DropDatabaseDoctrineCommand::class,
        'key:generate' => GenerateAppKeyCommand::class,
        'dump-env' => DumpEnvCommand::class,
    ])
];
