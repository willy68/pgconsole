<?php

namespace Application\Console;

use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Doctrine\Common\DataFixtures\Loader;
use Symfony\Component\Console\Input\InputOption;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;

/**
 * Based on the Symfony 2 DoctrineFixturesBundle.
 * The difference here is that the Symfony 2 version
 * is able to load fixtures from its registered bundles'
 * respective paths, which naturally doesn't work if
 * not using Symfony 2.
 */
class DataFixturesCommand extends DoctrineCommand
{
    protected function configure()
    {
        $this
            ->setName('fixtures:load')
            ->setDescription('Load data fixtures to your database.')
            ->addArgument(
                'fixtures-path',
                InputArgument::REQUIRED | InputArgument::IS_ARRAY,
                'The directory or file to load data fixtures from.'
            )
            ->addOption(
                'em',
                'e',
                InputOption::VALUE_OPTIONAL,
                'The EntityManager name to use for this command'
            )
            ->addOption(
                'append',
                null,
                InputOption::VALUE_NONE,
                'Append the data fixtures instead of deleting all data from the database first.'
            )
            ->addOption(
                'purge-with-truncate',
                null,
                InputOption::VALUE_NONE,
                'Purge data by using a database-level TRUNCATE statement'
            )
            ->setHelp(<<<EOT
The <info>fixtures:load</info> command loads data fixtures from the specified path:

  <info>console fixtures:load --append path/dir1 path/dir2 path/dir3</info>

You can specified EntityManager name for this operation
  <info>console fixtures:load --em=emName</info>

If you want to append the fixtures instead of flushing the database first you can use the <info>--append</info> option:

  <info>console fixtures:load --append</info>

By default Doctrine Data Fixtures uses DELETE statements to drop the existing rows from
the database. If you want to use a TRUNCATE statement instead you can use the <info>--purge-with-truncate</info> flag:

  <info>console fixtures:load --purge-with-truncate</info>
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $emName = $input->getOption('em');
        if (!empty($emName)) {
            $em = $this->getDoctrine()->getManager($emName);
        } else {
            $em = $this->getDoctrine()->getManager();
        }

        $paths = $input->getArgument('fixtures-path');
        if (! $paths) {
            throw new InvalidArgumentException("Please provide data fixtures path");
        }

        $loader = new Loader();
        foreach ($paths as $path) {
            if (is_dir($path)) {
                $loader->loadFromDirectory($path);
                continue;
            }
            $loader->loadFromFile($path);
        }
        $fixtures = $loader->getFixtures();
        if (!$fixtures) {
            throw new InvalidArgumentException(
                sprintf('Could not find any fixtures to load in: %s', "\n\n- " . implode("\n- ", $paths))
            );
        }
        /** @var EntityManagerInterface $em */
        $purger = new ORMPurger($em);
        $purger->setPurgeMode(
            ($input->getOption('purge-with-truncate'))
            ? ORMPurger::PURGE_MODE_TRUNCATE
            : ORMPurger::PURGE_MODE_DELETE
        );
        $executor = new ORMExecutor($em, $purger);
        $executor->setLogger(function ($message) use ($output) {
            $output->writeln(sprintf('  <comment>></comment> <info>%s</info>', $message));
        });
        $executor->execute($fixtures, $input->getOption('append'));
    }
}
