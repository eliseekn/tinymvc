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
 * Create new model observer.
 */
class Observer extends Command
{
    protected function configure(): void
    {
        $this->setName('make:observer');
        $this->setDescription('Create new model observer');
        $this->addArgument('observer', InputArgument::REQUIRED, 'The name of observer');
        $this->addOption('table', null, InputOption::VALUE_REQUIRED, 'The name of the model\'s table');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $observer = $input->getArgument('observer');
        $table = $input->getOption('table');

        [, $class] = Maker::generateClass($observer, singular: true, force_singular: true);

        if (! $this->createObserver($observer, $table)) {
            $output->writeln('<bg=red;options=bold> ERROR </> Failed to create observer <options=bold>'.Maker::fixPlural($class.'Observer', true).'</>.');
        } else {
            $output->writeln('<bg=blue;options=bold> INFO </> Observer <options=bold>'.Maker::fixPlural($class.'Observer', true).'</> has been created.');
        }

        return Command::SUCCESS;
    }

    public function createObserver(string $observer, string $table): bool
    {
        [, $class] = Maker::generateClass($observer, singular: true, force_singular: true);
        $className = Maker::fixPlural($class.'Observer', true);

        $data = Maker::stubs()->readFile('Observer.stub');
        $data = Maker::addNamespace($data, "App\Observers");
        $data = str_replace('CLASSNAME', $className, $data);
        $data = str_replace('TABLE_NAME', $table, $data);

        $storage = storage(config('storage.observers'));

        return $storage->writeFile($className.'.php', $data);
    }
}
