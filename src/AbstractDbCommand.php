<?php

namespace Application\Console;

use Psr\Container\ContainerInterface;

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
     * @var \PDO
     */
    protected $dao = null;

    public function __construct(ContainerInterface $c)
    {
        $this->dao = $c->get(\PDO::class);
        $this->db = $c->get('database.name');
    }
}
