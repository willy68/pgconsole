<?php

namespace Application\Console;

use PDO;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;

class ControllersCommand extends AbstractPHPCommand
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
        $this->dao = $c->get(PDO::class);
        $this->db = $c->get('database.name');
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('controller:all')
        ->setDescription('Controller:all create all controller based on db models.')
        ->setHelp('This command create all Controller based on db models with right name')
        ->setDefinition(
            new InputDefinition([
                new InputOption('namespace', 's', InputOption::VALUE_OPTIONAL),
                new InputOption('template', 't', InputOption::VALUE_OPTIONAL),
                new InputOption('dir', 'd', InputOption::VALUE_OPTIONAL),
            ])
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $namespace = $input->getOption('namespace');
        if ($namespace) {
            $this->namespace = $namespace;
        }
        $this->template = $input->getOption('template');
        $this->dir = $input->getOption('dir');

        return $this->makeController($input, $output);
    }

    /**
     * Make all controller
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function makeController(InputInterface $input, OutputInterface $output): int
    {
        $tables = $this->getTables($this->query . $this->db);
        $dir = $this->dir ?: $this->controllerDir;
        /** @var ConsoleOutputInterface $output */
        $sectionDir = $output->section();
        $io = new SymfonyStyle($input, $sectionDir);
        if ($this->createDir($dir) === -1) {
            $io->error('Fin du programme: Wrong directory');
            return -1;
        }
        $io->write("<info>Creation du dossier " . $dir . "</info>");

        $table = $tables->fetchAll(PDO::FETCH_NUM);
        $sectionFile = $output->section();
        /** @var FormatterHelper $formatter */
        $formatter = $this->getHelper('formatter');
        $sectionBar = $output->section();
        $ioBar = new SymfonyStyle($input, $sectionBar);
        $ioBar->progressStart(count($table));
        foreach ($table as $tab) {
            $modelName = $tab[0];
            $file = $dir . DIRECTORY_SEPARATOR . $this->getclassName($modelName) . 'Controller.php';
            if ($this->saveController($modelName, $file) === -1) {
                $formattedFileSection = $formatter->formatBlock(
                    "Le fichier " . $file . " existe déjà, opération non permise",
                    'error'
                );
                $sectionFile->overwrite($formattedFileSection);
                $ioBar->progressAdvance();
                usleep(500000);
                continue;
            }
            $sectionFile->overwrite("<info>Ecriture du fichier " . $file . "</info>");
            $ioBar->progressAdvance();
            usleep(500000);
        }
        $ioBar->progressFinish();
        return 0;
    }
}
