<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new mail template 
 */
class Mail extends Command
{
    protected static $defaultName = 'make:mail';

    protected function configure()
    {
        $this->setDescription('Create new mail template');
        $this->addArgument('mail', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of mail template (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mails = $input->getArgument('mail');

        foreach ($mails as $mail) {
            list(, $class) = Make::generateClass($mail, 'mail');

            if (!Make::createMail($mail)) {
                $output->writeln('<fg=yellow>Failed to create mail template "' . $class . '"</fg>');
            }

            $output->writeln('<info>Mail template "' . $class . '" has been created</info>');
        }

        return Command::SUCCESS;
    }
}
