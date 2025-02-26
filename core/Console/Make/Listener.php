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
 * Create new listener.
 */
class Listener extends Command
{
    protected static $defaultName = 'make:listener';

    protected function configure(): void
    {
        $this->setDescription('Create new event listener');
        $this->addArgument('listener', InputArgument::REQUIRED, 'The name of event listener');
        $this->addArgument('event', InputArgument::REQUIRED, 'Event name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $listener = $input->getArgument('listener');
        $event = $input->getArgument('event');

        if (! Maker::createListener($listener, $event)) {
            $output->writeln('<error>[ERROR] Failed to create listener "' . $listener) . '"</error>';
        } else {
            $output->writeln('<info>[ERROR] Listener "' . $listener) . '" has been created</info>';
        }

        return Command::SUCCESS;
    }
}
