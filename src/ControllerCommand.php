<?php

namespace Application\Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;

class ControllerCommand extends AbstractPHPCommand
{

    protected function configure()
    {
        $this->setName('controller')
        ->setDescription('Controller create controller based on db model.')
        ->setHelp('This command create Controller based on db model with right name')
        ->setDefinition(
            new InputDefinition([
                new InputOption('model', 'm', InputOption::VALUE_REQUIRED),
                new InputOption('namespace', 's', InputOption::VALUE_OPTIONAL),
                new InputOption('template', 't', InputOption::VALUE_OPTIONAL),
                new InputOption('dir', 'd', InputOption::VALUE_OPTIONAL),
            ])
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->model = $input->getOption('model');
        if (!$this->model) {
            $output->writeln('Le nom model est obligatoire');
            return -1;
        }
        $namespace = $input->getOption('namespace');
        if ($namespace) {
            $this->namespace = $namespace;
        }
        $this->template = $input->getOption('template');
        $this->dir = $input->getOption('dir');

        $model = ucfirst($this->model);
        $output->writeln("Create {$model}Controller.php");
        return $this->makeController($output);
    }

    /**
     * Make single controller
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     */
    public function makeController(OutputInterface $output): int
    {
        $model = $this->model;
        $dir = $this->dir ? $this->dir
            : $this->controllerDir;
        if ($this->createDir($dir, $output) === -1) {
            $output->writeln('Fin du programme: Wrong directory');
            return -1;
        }

        $file = $dir . DIRECTORY_SEPARATOR . $this->getclassName($model) . 'Controller.php';
        if ($this->saveController($model, $file, $output) === -1) {
            $output->writeln('Fin du programme: Wrong file' . $file);
            return -1;
        }
        return 0;
    }
}
