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
 * Create new controller.
 */
class Controller extends Command
{
    protected static $defaultName = 'make:controller';

    protected function configure(): void
    {
        $this->setDescription('Create new controller');
        $this->addArgument('controller', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of controller (separated by space if many)');
        $this->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Specify namespace (base: App\Http\Controllers)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $controllers = $input->getArgument('controller');

        foreach ($controllers as $controller) {
            [, $class] = Maker::generateClass($controller, 'controller', true, true);

            if (! $this->createController($controller, $input->getOption('namespace'))) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create controller <options=bold>'.$class.'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Controller <options=bold>'.$class.'</> has been created.');
            }
        }

        return Command::SUCCESS;
    }

    public function createController(string $controller, ?string $namespace = null): bool
    {
        [, $class] = Maker::generateClass($controller, 'controller', true, true);

        $data = Maker::stubs()->readFile('Controller.stub');
        $data = Maker::addNamespace($data, 'App\Http\Controllers', $namespace);
        $data = str_replace('CLASSNAME', $class, $data);

        $storage = storage(config('storage.controllers'));

        if (! is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        return $storage->writeFile($class.'.php', $data);
    }
}
