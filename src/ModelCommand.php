<?php

namespace Application\Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;

class ModelCommand extends AbstractModelCommand
{

    protected function configure()
    {
        $this->setName('model')
        ->setDescription('model create ActiveRecord model class based on db model.')
        ->setHelp('This command create ActiveRecord model class based based on db model with right name')
        ->setDefinition(
            new InputDefinition([
                new InputOption('table', 't', InputOption::VALUE_REQUIRED),
                new InputOption('namespace', 's', InputOption::VALUE_OPTIONAL),
                // new InputOption('template', 't', InputOption::VALUE_OPTIONAL),
                new InputOption('dir', 'd', InputOption::VALUE_OPTIONAL),
            ])
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->model = $input->getOption('table');
        if (!$this->model) {
            $output->writeln('Le nom model est obligatoire');
            return -1;
        }
        $namespace = $input->getOption('namespace');
        if ($namespace) {
            $this->namespace = $namespace;
        }
        // $this->template = $input->getOption('template');
        $this->dir = $input->getOption('dir');

        return $this->makeModel($output);
    }

    /**
     *
     *
     * @param OutputInterface $output
     * @return int
     */
    public function makeModel(OutputInterface $output): int
    {
        $model = $this->model;
        $dir = $this->dir ? $this->dir
            : $this->modelDir;
        if ($this->createDir($dir, $output) === -1) {
            $output->writeln('Fin du programme: Wrong directory');
            return -1;
        }

        $file = $dir . DIRECTORY_SEPARATOR . $this->getclassName($model) . '.php';
        if ($this->saveModel($model, $file, $output) === -1) {
            $output->writeln('Fin du programme: Wrong file' . $file);
            return -1;
        }
        return 0;
    }
}
