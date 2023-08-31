<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\Database;

use Spatie\StructureDiscoverer\Discover;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Run seeders
 */
class Seed extends Command
{
    protected static $defaultName = 'db:seed';

    protected function configure(): void
    {
        $this->setDescription('Run seeders');
        $this->addArgument('seeder', InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'The name of seeders (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $seeders = $input->getArgument('seeder');

        if (empty($seeders)) {
            $seeders = Discover::in(config('storage.seeders'))->classes()->get();

            foreach ($seeders as $seeder) {
                $seeder::run();
            }

            $output->writeln('<info>[INFO] All seeders have been run</info>');
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
        $output->writeln('<info>[INFO] Seeder "' . $seeder . '" has been run</info>');
    }
}
