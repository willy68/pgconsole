<?php

declare(strict_types=1);

namespace Application\Console;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\EntityManagerProvider;
use Doctrine\Persistence\ManagerRegistry;

class ManagerRegistryProvider implements EntityManagerProvider
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getDefaultManager(): EntityManagerInterface
    {
        /** @var EntityManagerInterface $em */
        $em = $this->managerRegistry->getManager();
        return $em;
    }

    /**
     * @param string $name
     * @return EntityManagerInterface
     */
    public function getManager(string $name): EntityManagerInterface
    {
        /** @var EntityManagerInterface $em */
        $em = $this->managerRegistry->getManager($name);
        return $em;
    }
}