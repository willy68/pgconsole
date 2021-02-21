<?php

namespace Application\Console;

use Application\Console\ConsoleApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateAppKeyCommand extends Command
{

    protected function configure()
    {
        $this->setName('key:generate')
        ->setDescription('Set the application key')
        ->setHelp('Set the application key (generate key:generate)')
        ->setDefinition(
            new InputDefinition([
                new InputOption('show', 's', InputOption::VALUE_OPTIONAL)
            ])
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $key = $this->getRandomKey();

        $io = new SymfonyStyle($input, $output);
        if ($input->getOption('show')) {
            $io->write('<comment>' . $key . '</comment>');
        }

        /** @var ConsoleApplication $app */
        $app = $this->getApplication();
        $path = $app->getBasePath() . '/.env';

        if (file_exists($path)) {
            file_put_contents(
                $path,
                preg_replace('/(APP_KEY=)(\s|.*)\n/', ("APP_KEY={$key}\n"), file_get_contents($path))
            );
        }

        $io->info("Application key [$key] set successfully.");
        
        return 0;
    }

    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function getRandomKey()
    {
        return base64_encode(random_bytes(32));
    }
}
