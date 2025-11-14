<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new console command.
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
        [, $class] = Maker::generateClass($input->getArgument('console'), '', true);

        if (! $this->createConsole(
            $input->getArgument('console'),
            $input->getOption('command'),
            $input->getOption('description'),
            $input->getOption('namespace')
        )) {
            $output->writeln('<bg=red;options=bold> ERROR </> Failed to create command <options=bold>'.$class.'</>.');
        } else {
            $output->writeln('<bg=blue;options=bold> INFO </> Command <options=bold>'.$class.'</> has been created.');
        }

        return Command::SUCCESS;
    }

    public function createConsole(string $console, string $command, string $description, ?string $namespace = null): bool
    {
        [, $class] = Maker::generateClass($console, '', true);

        $data = Maker::stubs()->readFile('Console.stub');
        $data = Maker::addNamespace($data, 'App\Console', $namespace);
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('COMMAND_NAME', $command, $data);
        $data = str_replace('COMMAND_DESCRIPTION', $description, $data);

        $storage = storage(config('storage.console'));

        if (! is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        return $storage->writeFile($class.'.php', $data);
    }
}
