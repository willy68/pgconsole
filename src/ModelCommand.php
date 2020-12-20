<?php

namespace Application\Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
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
        $io = new SymfonyStyle($input, $output);
        if (!$this->model) {
            $io->caution('Le nom du model est obligatoire');
            return -1;
        }
        $namespace = $input->getOption('namespace');
        if ($namespace) {
            $this->namespace = $namespace;
        }
        // $this->template = $input->getOption('template');
        $this->dir = $input->getOption('dir');

        return $this->makeModel($io);
    }

    /**
     * 
     *
     * @param \Symfony\Component\Console\Style\SymfonyStyle $io
     * @return int
     */
    public function makeModel(SymfonyStyle $io): int
    {
        $model = $this->model;
        $dir = $this->dir ? $this->dir
            : $this->modelDir;
        if ($this->createDir($dir, $io) === -1) {
            $io->error('Fin du programme: Wrong directory');
            return -1;
        }

        $file = $dir . DIRECTORY_SEPARATOR . $this->getclassName($model) . '.php';
        if ($this->saveModel($model, $file, $io) === -1) {
            $io->error('Fin du programme: Wrong file' . $file);
            return -1;
        }
        return 0;
    }
}
