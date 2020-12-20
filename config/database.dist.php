<?php

use Psr\Container\ContainerInterface;

return [
    'database.sgdb' => 'mysql',
    'database.host' => 'localhost',
    'database.user' => 'root',
    'database.password' => 'root',
    'database.name' => 'my_database',
    'database.models.dir' => dirname(__DIR__) . '/app/Models',
    'ActiveRecord.connections' => function (ContainerInterface $c): array {
        return [
            'development' => $c->get('database.sgdb') . "://" .
                $c->get('database.user') . ":" .
                $c->get('database.password') . "@" .
                $c->get('database.host') . "/" .
                $c->get('database.name') . "?charset=utf8",
            'ajax' => $c->get('database.sgdb') . "://" .
                $c->get('database.user') . ":" .
                $c->get('database.password') . "@" .
                $c->get('database.host') . "/" .
                "communes?charset=utf8"
        ];
    }
];
