<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new listener
 */
class Listener extends Command
{
    protected static $defaultName = 'make:listener';

    protected function configure(): void
    {
        $this->setDescription('Create new listener');
        $this->addArgument('listener', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of event listener table (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $listeners = $input->getArgument('listener');

        foreach ($listeners as $listener) {
            list(, $class) = Maker::generateClass(base_name: $listener, singular: true, force_singlular: true);

            if (!Maker::createListener($listener)) {
                $output->writeln('<error>[ERROR] Failed to create listener "' . Maker::fixPluralTypo($class . 'EventListener', true) . '"</error>');
            }

            $output->writeln('<info>[INFO] Factory "' . Maker::fixPluralTypo($class . 'EventListener', true) . '" has been created</info>');
        }

        return Command::SUCCESS;
    }
}
