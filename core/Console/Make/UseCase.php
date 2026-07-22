<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new use cases.
 */
class UseCase extends Command
{
    protected function configure(): void
    {
        $this->setName('make:use-case');
        $this->setDescription('Create new use case');
        $this->addOption('model', null, InputOption::VALUE_OPTIONAL, 'The name of model');
        $this->addOption('type', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Specify use case type (index, show, store, update, delete or custom name)');
        $this->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Specify namespace (base: App\UseCases)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $types = $input->getOption('type');

        if (empty($types)) {
            $types = ['index', 'show', 'store', 'update', 'delete'];
        }

        $types = array_map(fn ($type) => strtolower($type), $types);

        foreach ($types as $type) {
            [, $class] = Maker::generateClass($type, 'use_case', true, true);
            $class = str_replace(['Index', 'Show'], ['GetCollection', 'GetItem'], $class);

            if (! $this->createUseCase($type, $input->getOption('model'), $input->getOption('namespace'))) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create use case <options=bold>'.$class.'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Use case <options=bold>'.$class.'</> has been created.');
            }
        }

        return Command::SUCCESS;
    }

    public function createUseCase(string $type, ?string $model = null, ?string $namespace = null): bool
    {
        [$type, $class] = Maker::generateClass($type, 'use_case', true, true);
        $class = str_replace(['Index', 'Show'], ['GetCollection', 'GetItem'], $class);

        if (is_null($model)) {
            $type = 'blank';
        }

        $data = Maker::stubs()->addPath('useCases')->readFile($type.'.stub');

        if (! is_null($model)) {
            [$name] = Maker::generateClass($model, 'use_case', true, true);
            $namespace = is_null($namespace) ? ucfirst($name) : $namespace.'\\'.ucfirst($name);
            $data = str_replace('$MODEL_NAME', '$'.Maker::fixPlural($name, true), $data);
            $data = str_replace('MODEL_NAME', Maker::fixPlural(ucfirst($name), true), $data);
        }

        $data = Maker::addNamespace($data, 'App\UseCases', $namespace);
        $data = str_replace('CLASSNAME', $class, $data);

        $storage = storage(config('storage.useCases'));

        if (! is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        return $storage->writeFile($class.'.php', $data);
    }
}
