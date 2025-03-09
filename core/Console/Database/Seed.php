<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Database;

use Spatie\StructureDiscoverer\Discover;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Run seeders.
 */
class Seed extends Command
{
    protected static $defaultName = 'db:seed';

    protected function configure(): void
    {
        $this->setDescription('Run seeders');
        $this->addArgument('seeder', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'The name of seeders (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $seeders = $input->getArgument('seeder');

        if (empty($seeders)) {
            $seeders = Discover::in(config('storage.seeders'))->classes()->get();

            foreach ($seeders as $seeder) {
                // @phpstan-ignore-next-line
                $seeder::run();
            }

            $output->writeln('<bg=blue;options=bold> INFO </> All seeders have been run.');
        } else {
            foreach ($seeders as $seeder) {
                $this->seed($output, $seeder);
            }
        }

        return Command::SUCCESS;
    }

    protected function seed(OutputInterface $output, string $seeder): void
    {
        $seeder = '\App\Database\Seeders\\' . $seeder;
        $seeder::run();
        $output->writeln('<bg=blue;options=bold> Seeder <options=bold>' . $seeder . '</> has been run.');
    }
}
