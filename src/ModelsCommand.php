<?php

namespace Application\Console;

use PDO;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;

class ModelsCommand extends AbstractModelCommand
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
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('model:all')
        ->setDescription('model:all create all ActiveRecord model class based on db models.')
        ->setHelp('This command create all ActiveRecord model class based based on db models with right name')
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

        return $this->makeModels($output);
    }

    /**
     *
     *
     * @param OutputInterface $output
     * @return int
     */
    public function makeModels(OutputInterface $output): int
    {
        $tables = $this->getTables($this->query . $this->db);
  
        $dir = $this->dir ? $this->dir : $this->modelDir;
        if ($this->createDir($dir, $output) === -1) {
            $output->writeln('Fin du programme: Wrong directory');
            return -1;
        }
  
        while ($table = $tables->fetch(\PDO::FETCH_NUM)) {
            $model = $table[0];
            $file = $dir . DIRECTORY_SEPARATOR . $this->getclassName($model) . '.php';
            if ($this->saveModel($model, $file, $output) === -1) {
                continue;
            }
        }
        return 0;
    }
}
