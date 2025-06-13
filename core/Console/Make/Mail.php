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
 * Create new mail template.
 */
class Mail extends Command
{
    protected static $defaultName = 'make:mail';

    protected function configure(): void
    {
        $this->setDescription('Create new mail');
        $this->addArgument('mail', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of mail (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $mails = $input->getArgument('mail');

        foreach ($mails as $mail) {
            [, $class] = Maker::generateClass($mail, 'mail', force_singular: true);

            if (! Maker::createMail($mail)) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create mail <options=bold>'.$class.'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Mail <options=bold>'.$class.'</> has been created.');
            }
        }

        return Command::SUCCESS;
    }
}
