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
        $listener = Maker::removeUnderscore($listener);
        $event = $input->getArgument('event');

        if (! $this->createListener($listener, $event)) {
            $output->writeln('<bg=red;options=bold> ERROR </> Failed to create listener <options=bold>'.$listener.'</>.');
        } else {
            $output->writeln('<bg=blue;options=bold> INFO </> Listener <options=bold>'.$listener.'</> has been created.');
        }

        return Command::SUCCESS;
    }

    public function createListener(string $listener, string $event): bool
    {
        $data = Maker::stubs()->addPath('events')->readFile('Listener.stub');
        $data = Maker::addNamespace($data, "App\Events\\".$event);
        $data = str_replace('CLASSNAME', $listener, $data);
        $data = str_replace('EVENT', $event.'Event', $data);

        $storage = storage(config('storage.events'));
        $storage = $storage->addPath($event);

        return $storage->writeFile($listener.'.php', $data);
    }
}
