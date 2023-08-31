<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new seeder
 */
class Seeder extends Command
{
    protected static $defaultName = 'make:seeder';

    protected function configure(): void
    {
        $this->setDescription('Create new seeder');
        $this->addArgument('seeder', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of the model (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $seeders = $input->getArgument('seeder');

        foreach ($seeders as $seeder) {
            list(, $class) = Maker::generateClass($seeder, 'seeder', true, true);

            if (!Maker::createSeeder($seeder)) {
                $output->writeln('<error>[ERROR] Failed to create seeder "' . Maker::fixPluralTypo($class, true) . '"</error>');
            }

            $output->writeln('<info>[INFO] Seeder "' . Maker::fixPluralTypo($class, true) . '" has been created</info>');
        }

        return Command::SUCCESS;
    }
}
