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
 * Create new helper 
 */
class Helper extends Command
{
    protected static $defaultName = 'make:helper';

    protected function configure(): void
    {
        $this->setDescription('Create new helper');
        $this->addArgument('helper', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of helper (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helpers = $input->getArgument('helper');

        foreach ($helpers as $helper) {
            list(, $class) = Maker::generateClass($helper);

            if (!Maker::createHelper($helper)) {
                $output->writeln('<error>[ERROR] Failed to create helper "' . $class . '"</error>');
            }

            $output->writeln('<info>[INFO] Helper "' . $class . '" has been created</info>');
        }

        return Command::SUCCESS;
    }
}
