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
 * Create new event.
 */
class Event extends Command
{
    protected function configure(): void
    {
        $this->setName('make:event');
        $this->setDescription('Create new event');
        $this->addArgument('event', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of event table (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $events = $input->getArgument('event');

        foreach ($events as $event) {
            [, $class] = Maker::generateClass($event, singular: true, force_singular: true);

            if (! $this->createEvent($event)) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create event  <options=bold>'.Maker::fixPlural($class.'Event', true).'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Event <options=bold>'.Maker::fixPlural($class.'Event', true).'</> has been created.');
            }
        }

        return Command::SUCCESS;
    }

    public function createEvent(string $event): bool
    {
        [, $class] = Maker::generateClass($event, singular: true, force_singular: true);
        $className = Maker::fixPlural($class.'Event', true);

        $data = Maker::stubs()->addPath('events')->readFile('Event.stub');
        $data = Maker::addNamespace($data, "App\Events\\".Maker::fixPlural($class, true));
        $data = str_replace('CLASS_NAME', $className, $data);

        $storage = storage(config('storage.events'));
        $storage = $storage->addPath(Maker::fixPlural($class, true));

        return $storage->writeFile($className.'.php', $data);
    }
}
