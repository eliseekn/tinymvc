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
 * Create new custom exception.
 */
class Exception extends Command
{
    protected static $defaultName = 'make:exception';

    protected function configure(): void
    {
        $this->setDescription('Create new custom exception');
        $this->addArgument('exception', InputArgument::REQUIRED, 'The name of exception');
        $this->addOption('message', 'm', InputOption::VALUE_REQUIRED, 'The exception message');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exception = $input->getArgument('exception');
        $message = $input->getOption('message');

        [, $class] = Maker::generateClass($exception);

        if (! Maker::createException($exception, $message)) {
            $output->writeln('<bg=red;options=bold> ERROR </> Failed to create exception <options=bold>'.$class.'</>.');
        } else {
            $output->writeln('<bg=blue;options=bold> INFO </> Exception <options=bold>'.$class.'</> has been created.');
        }

        return Command::SUCCESS;
    }
}
