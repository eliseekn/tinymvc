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
class Notification extends Command
{
    protected static $defaultName = 'make:notification';

    protected function configure(): void
    {
        $this->setDescription('Create new mail');
        $this->addArgument('type', InputArgument::REQUIRED, 'The type of notification');
        $this->addArgument('notification', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of notification (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $notifications = $input->getArgument('notification');
        $type = $input->getArgument('type');

        foreach ($notifications as $notification) {
            [, $class] = Maker::generateClass($notification, $type, force_singular: true);

            if (! Maker::createNotification($notification, $type)) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create notification <options=bold>'.$class.'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Notification <options=bold>'.$class.'</> has been created.');
            }
        }

        return Command::SUCCESS;
    }
}
