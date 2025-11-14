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
 * Create new model factory.
 */
class Factory extends Command
{
    protected static $defaultName = 'make:factory';

    protected function configure(): void
    {
        $this->setDescription('Create new model factory');
        $this->addArgument('factory', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of model factory table (separated by space if many)');
        $this->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Specify namespace (base: App\Database\Factories)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $factories = $input->getArgument('factory');

        foreach ($factories as $factory) {
            [, $class] = Maker::generateClass($factory, 'factory', true, true);

            if (! $this->createFactory($factory, $input->getOption('namespace'))) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create factory <options=bold>'.Maker::fixPlural($class, true).'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Factory <options=bold>'.Maker::fixPlural($class, true).'</> has been created.');
            }
        }

        return Command::SUCCESS;
    }

    public function createFactory(string $factory, ?string $namespace = null): bool
    {
        [$name, $class] = Maker::generateClass($factory, 'factory', true, true);

        $data = Maker::stubs()->addPath('database')->readFile('Factory.stub');
        $data = Maker::addNamespace($data, 'App\Database\Factories', $namespace);
        $data = str_replace('CLASSNAME', Maker::fixPlural($class, true), $data);
        $data = str_replace('MODEL_NAME', Maker::fixPlural(ucfirst($name), true), $data);

        $storage = storage(config('storage.factories'));

        if (! is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        return $storage->writeFile(Maker::fixPlural($class, true).'.php', $data);
    }
}
