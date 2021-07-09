<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\Make;

use Core\Support\Encryption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate hashed password
 */
class Password extends Command
{
    protected static $defaultName = 'make:password';

    protected function configure()
    {
        $this->setDescription('Generate hashed password');
        $this->addArgument('password', InputArgument::REQUIRED, 'The password to hash');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>' . $input->getArgument('password') . '</info> => <info>' . hash_pwd($input->getArgument('password')) . '</info>');
        return Command::SUCCESS;
    }
}
