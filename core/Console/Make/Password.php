<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate hashed password.
 */
class Password extends Command
{
    protected static $defaultName = 'make:password';

    protected function configure(): void
    {
        $this->setDescription('Generate hashed password');
        $this->addArgument('password', InputArgument::REQUIRED, 'The password to hash');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>' . hash($input->getArgument('password')) . '</info>');

        return Command::SUCCESS;
    }
}
