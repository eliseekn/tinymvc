<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new model factory 
 */
class Factory extends Command
{
    protected static $defaultName = 'make:factory';

    protected function configure(): void
    {
        $this->setDescription('Create new model factory');
        $this->addArgument('factory', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of model factory table (separated by space if many)');
        $this->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Specify namespace (base: App\Database\Factories)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $factories = $input->getArgument('factory');

        foreach ($factories as $factory) {
            list(, $class) = Maker::generateClass($factory, 'factory', true, true);

            if (!Maker::createFactory($factory, $input->getOption('namespace'))) {
                $output->writeln('<error>[ERROR] Failed to create factory "' . Maker::fixPlural($class, true) . '"</error>');
            } else {
                $output->writeln('<info>[INFO] Factory "' . Maker::fixPlural($class, true) . '" has been created</info>');
            }
        }

        return Command::SUCCESS;
    }
}
