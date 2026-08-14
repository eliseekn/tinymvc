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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new resource.
 */
class Resource extends Command
{
    protected function configure(): void
    {
        $this->setName('make:resource');
        $this->setDescription('Create new resource');
        $this->addArgument('resource', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of resource (separated by space if many)');
        $this->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Specify namespace (base: App\Http\Resources)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $resources = $input->getArgument('resource');

        foreach ($resources as $resource) {
            [, $class] = Maker::generateClass($resource, 'resource', true, true);

            if (! $this->createResource($resource, $input->getOption('namespace'))) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create resource <options=bold>'.$class.'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Resource <options=bold>'.$class.'</> has been created.');
            }
        }

        return Command::SUCCESS;
    }

    public function createResource(string $resource, ?string $namespace = null): bool
    {
        [, $class] = Maker::generateClass($resource, 'resource', true, true);

        $data = Maker::stubs()->readFile('Resource.stub');
        $data = Maker::addNamespace($data, 'App\Http\Resources', $namespace);
        $data = str_replace('CLASS_NAME', $class, $data);

        $storage = storage(config('storage.resources'));

        if (! is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        return $storage->writeFile($class.'.php', $data);
    }
}
