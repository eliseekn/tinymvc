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
 * Create new console command 
 */
class Console extends Command
{
    protected static $defaultName = 'make:console';

    protected function configure(): void
    {
        $this->setDescription('Create new console command');
        $this->addArgument('console', InputArgument::REQUIRED, 'The console class name');
        $this->addOption('command', null, InputOption::VALUE_REQUIRED, 'The console name');
        $this->addOption('description', null, InputOption::VALUE_REQUIRED, 'The console description (inside "")');
        $this->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Specify namespace (base: App\Console)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        list(, $class) = Maker::generateClass($input->getArgument('console'), '', true);

        if (!Maker::createConsole($input->getArgument('console'), $input->getOption('command'), $input->getOption('description'), $input->getOption('namespace'))) {
            $output->writeln('<error>[ERROR] Failed to create command "' . $class . '"</error>');
        }

        $output->writeln('<info>[INFO] Command "' . $class . '" has been created</info>');

        return Command::SUCCESS;
    }
}
