<?php

/*
 *  From Doctrine Bundle Symfony
*/

namespace Application\Console;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;

/**
 * Base class for Doctrine console commands to extend from.
 *
 * @internal
 */
abstract class DoctrineCommand extends Command
{
    /** @var ManagerRegistry */
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        parent::__construct();

        $this->doctrine = $doctrine;
    }

    /**
     * get a doctrine entity generator
     *
     * @return //EntityGenerator
     */
	/*
    protected function getEntityGenerator(): EntityGenerator
    {
        $entityGenerator = new EntityGenerator();
        $entityGenerator->setGenerateAnnotations(false);
        $entityGenerator->setGenerateStubMethods(true);
        $entityGenerator->setRegenerateEntityIfExists(false);
        $entityGenerator->setUpdateEntityIfExists(true);
        $entityGenerator->setNumSpaces(4);
        $entityGenerator->setAnnotationPrefix('ORM\\');

        return $entityGenerator;
    }
	*/

    /**
     * Get a doctrine entity manager by symfony name.
     *
     * @param string $name
     *
     * @return ObjectManager
     */
    protected function getEntityManager(string $name): ObjectManager
    {
        return $this->getDoctrine()->getManager($name);
    }

    /**
     * Get a doctrine dbal connection by symfony name.
     *
     * @param string $name
     * @return object
     */
    protected function getDoctrineConnection(string $name)
    {
        return $this->getDoctrine()->getConnection($name);
    }

    /** @return ManagerRegistry */
    protected function getDoctrine(): ManagerRegistry
    {
        return $this->doctrine;
    }
}
