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
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new model file.
 */
class Model extends Command
{
    protected function configure(): void
    {
        $this->setName('make:model');
        $this->setDescription('Create new model');
        $this->addArgument('model', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of model (separated by space if many)');
        $this->addOption('migration', 'm', InputOption::VALUE_NONE, 'Create new migration');
        $this->addOption('controller', 'c', InputOption::VALUE_NONE, 'Create new controller');
        $this->addOption('factory', 'f', InputOption::VALUE_NONE, 'Create new factory');
        $this->addOption('seed', 's', InputOption::VALUE_NONE, 'Create new seed');
        $this->addOption('entity', 'e', InputOption::VALUE_NONE, 'Create new entity');
        $this->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Specify namespace (base: App\Database\Models)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $models = $input->getArgument('model');

        foreach ($models as $model) {
            [$name, $class] = Maker::generateClass($model, 'model', true, true);

            if (! $this->createModel($name, $input->getOption('namespace'))) {
                $output->writeln('<bg=red;options=bold> ERROR </>  Failed to create model <options=bold>'.Maker::fixPlural($class, true).'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Model <options=bold>'.Maker::fixPlural($class, true).'</> has been created.');

                $this->getApplication()->find('make:entity')->run(new ArrayInput(['entity' => [$model]]), $output);

                if ($input->getOption('migration')) {
                    $this->getApplication()->find('make:migration')->run(new ArrayInput(['migration' => [$model]]), $output);
                }

                if ($input->getOption('controller')) {
                    $this->getApplication()->find('make:controller')->run(new ArrayInput([
                        'controller' => [$model],
                        '--namespace' => $input->getOption('namespace'),
                    ]), $output);
                }

                if ($input->getOption('factory')) {
                    $this->getApplication()->find('make:factory')->run(new ArrayInput(['factory' => [$model]]), $output);
                }

                if ($input->getOption('seed')) {
                    $this->getApplication()->find('make:seeder')->run(new ArrayInput(['seeder' => [$model]]), $output);
                }
            }
        }

        return Command::SUCCESS;
    }

    public function createModel(string $model, ?string $namespace = null): bool
    {
        [$name, $class] = Maker::generateClass($model, 'model', true, true);

        $data = Maker::stubs()->addPath('database')->readFile('Model.stub');
        $data = Maker::addNamespace($data, 'App\Database\Models', $namespace);
        $data = str_replace('CLASSNAME', Maker::fixPlural($class, true), $data);
        $data = str_replace('TABLE_NAME', $name, $data);
        $data = str_replace('ENTITY_NAME', ucfirst($model), $data);
        $data = str_replace('FACTORY_NAME', ucfirst($model).'Factory', $data);

        $storage = storage(config('storage.models'));

        if (! is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        return $storage->writeFile(Maker::fixPlural($class, true).'.php', $data);
    }
}
