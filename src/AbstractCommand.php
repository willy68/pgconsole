<?php

namespace Application\Console;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class AbstractCommand extends SymfonyCommand
{
    protected $controllerDir = null;

    protected $modelDir = null;

    public function __construct()
    {
        $this->controllerDir = 'generated_controllers';
        $this->modelDir = 'generated_models';
        parent::__construct();
    }

    /**
     * Create dir
     *
     * @param string $dir
     * @param \Symfony\Component\Console\Style\SymfonyStyle $io
     * @return int
     */
    protected function createDir(string $dir, SymfonyStyle $io): int
    {
        if (!is_dir($dir)) {
            $oldumask = umask(0);
            if (!mkdir($dir, 0777, true)) {
                umask($oldumask);
                $io->error('Impossible de créer le dossier ' . $dir);
                return -1;
            }
            umask($oldumask);
            $io->text("Creation du dossier " . $dir);
        }
        return 0;
    }

    /**
     * Save file
     *
     * @param string $model
     * @param string $filename
     * @param \Symfony\Component\Console\Style\SymfonyStyle $io
     * @return int
     */
    protected function saveFile(string $model, string $filename, SymfonyStyle $io): int
    {
        if (!file_exists($filename)) {
            if (($handle = fopen($filename, 'x'))) {
                fwrite($handle, $model);
                fclose($handle);
                chmod($filename, 0666);
                $io->text("Ecriture du fichier " . $filename);
                return 0;
            }
        } else {
            $io->caution("Le fichier " . $filename . " existe déjà, opération non permise");
            return -1;
        }
    }

    /**
     * @param string $fieldName
     * @return string
     */
    protected function getclassName(string $modelName): string
    {
        return join('', array_map('ucfirst', explode('_', $modelName)));
    }
}
