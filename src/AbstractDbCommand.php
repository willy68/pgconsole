<?php

namespace Application\Console;

use PDO;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AbstractDbCommand extends AbstractCommand
{
    use DatabaseCommandTrait;

    /**
     *
     *
     * @var string
     */
    protected $query = "SHOW TABLES FROM ";

    /**
     * Name of table model
     *
     * @var string
     */
    protected $db = null;

    /**
     * pdo instance
     *
     * @var PDO
     */
    protected $dao = null;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $c)
    {
        parent::__construct();
        $this->dao = $c->get(PDO::class);
        $this->db = $c->get('database.name');
    }
}
