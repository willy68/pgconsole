<?php

namespace Application\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;

class AbstractCommand extends SymfonyCommand
{
    use FileDirTrait;

    protected $controllerDir = null;

    protected $modelDir = null;

    public function __construct()
    {
        $this->controllerDir = 'generated_controllers';
        $this->modelDir = 'generated_models';
        parent::__construct();
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
