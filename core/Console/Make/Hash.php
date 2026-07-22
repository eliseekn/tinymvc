<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
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
 * Generate hashed string.
 */
class Hash extends Command
{
    protected function configure(): void
    {
        $this->setName('make:hash');
        $this->setDescription('Generate hashed string');
        $this->addArgument('string', InputArgument::REQUIRED, 'The string to hash');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>'.bcrypt($input->getArgument('string')).'</info>');

        return Command::SUCCESS;
    }
}
