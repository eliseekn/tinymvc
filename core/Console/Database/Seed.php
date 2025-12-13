<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Database;

use App\Database\Seeders\Seeders;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Run seeders.
 */
class Seed extends Command
{
    protected function configure(): void
    {
        $this->setName('db:seed');
        $this->setDescription('Run seeders');
        $this->addArgument('seeder', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'The name of seeders (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $seeders = ! empty($input->getArgument('seeder'))
            ? $input->getArgument('seeder')
            : Seeders::get();

        foreach ($seeders as $seeder) {
            $this->seed($output, $seeder);
        }

        $output->writeln('<bg=blue;options=bold> INFO </> All seeders have been run.');

        return Command::SUCCESS;
    }

    protected function seed(OutputInterface $output, string $seeder): void
    {
        if (str_contains($seeder, '\\')) {
            $seeder = explode('\\', $seeder);
            $seeder = end($seeder);
        }

        $seederClass = '\App\Database\Seeders\\'.$seeder;
        $seederClass::run();
        $output->writeln('<bg=blue;options=bold> INFO </> Seeder <options=bold>'.$seeder.'</> has been run.');
    }
}
