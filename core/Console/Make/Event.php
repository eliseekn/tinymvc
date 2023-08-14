<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new event
 */
class Event extends Command
{
    protected static $defaultName = 'make:event';

    protected function configure(): void
    {
        $this->setDescription('Create new event');
        $this->addArgument('event', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of event table (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $events = $input->getArgument('event');

        foreach ($events as $event) {
            list(, $class) = Make::generateClass(base_name: $event, singular: true, force_singlular: true);

            if (!Make::createEvent($event)) {
                $output->writeln('<fg=yellow>Failed to create event "' . Make::fixPluralTypo($class . 'Event', true) . '"</fg>');
            }

            $output->writeln('<info>Factory "' . Make::fixPluralTypo($class . 'Event', true) . '" has been created</info>');
            $this->getApplication()->find('make:listener')->run(new ArrayInput(['listener' => [$event]]), $output);
        }

        return Command::SUCCESS;
    }
}
