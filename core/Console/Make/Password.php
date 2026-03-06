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
 * Generate password.
 */
class Password extends Command
{
    protected function configure(): void
    {
        $this->setName('make:password');
        $this->setDescription('Generate random password');
        $this->addArgument('length', InputArgument::REQUIRED, 'The password length');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $password = random_string((int) $input->getArgument('length'), true);

        $output->writeln('<comment>Raw</comment>: <info>'.$password.'</info>');
        $output->writeln('<comment>Hashed</comment>: <info>'.bcrypt($password).'</info>');

        return Command::SUCCESS;
    }
}
