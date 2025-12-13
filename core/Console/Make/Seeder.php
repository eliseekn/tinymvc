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
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new seeder.
 */
class Seeder extends Command
{
    protected function configure(): void
    {
        $this->setName('make:seeder');
        $this->setDescription('Create new seeder');
        $this->addArgument('seeder', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of the model (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $seeders = $input->getArgument('seeder');

        foreach ($seeders as $seeder) {
            [, $class] = Maker::generateClass($seeder, 'seeder', true, true);

            if (! $this->createSeeder($seeder)) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create seeder <options=bold>'.Maker::fixPlural($class, true).'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Seeder <options=bold>'.Maker::fixPlural($class, true).'</> has been created.');
            }
        }

        return Command::SUCCESS;
    }

    public function createSeeder(string $seeder): bool
    {
        [$name, $class] = Maker::generateClass($seeder, 'seeder', true, true);

        $data = Maker::stubs()->addPath('database')->readFile('Seeder.stub');
        $data = str_replace('CLASSNAME', Maker::fixPlural($class, true), $data);
        $data = str_replace('MODEL_NAME', Maker::fixPlural(ucfirst($name), true), $data);

        return storage(config('storage.seeders'))->writeFile(Maker::fixPlural($class, true).'.php', $data);
    }
}
