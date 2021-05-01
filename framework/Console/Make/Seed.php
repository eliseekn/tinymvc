<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Console\Make;

use Framework\Console\Make;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new seed 
 */
class Seed extends Command
{
    protected static $defaultName = 'make:seed';

    protected function configure()
    {
        $this->setDescription('Create new seed');
        $this->addArgument('seed', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of seed table (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $seeds = $input->getArgument('seed');

        foreach ($seeds as $seed) {
            list($name, $class) = Make::generateClass($seed, 'seed');

            if (!Make::createSeed($seed)) {
                $output->writeln('<fg=yellow>Failed to create seed "' . $class . '"</fg>');
            }

            $output->writeln('<info>Seed "' . $class . '" has been created</info>');
        }

        return Command::SUCCESS;
    }
}
