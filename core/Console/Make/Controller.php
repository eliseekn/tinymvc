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
 * Create new controller 
 */
class Controller extends Command
{
    protected static $defaultName = 'make:controller';

    protected function configure(): void
    {
        $this->setDescription('Create new controller');
        $this->addArgument('controller', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of controller (separated by space if many)');
        $this->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Specify namespace (base: App\Http\Controllers)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $controllers = $input->getArgument('controller');

        foreach ($controllers as $controller) {
            list(, $class) = Maker::generateClass($controller, 'controller', true, true);

            if (!Maker::createController($controller, $input->getOption('namespace'))) {
                $output->writeln('<error>[ERROR] Failed to create controller "' . $class . '"</error>');
            } else {
                $output->writeln('<info>[INFO] Controller "' . $class . '" has been created</info>');
            }
        }

        return Command::SUCCESS;
    }
}
