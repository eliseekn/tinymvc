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
 * Create new actions 
 */
class Actions extends Command
{
    protected static $defaultName = 'make:action';

    protected function configure(): void
    {
        $this->setDescription('Create new action');
        $this->addArgument('model', InputArgument::REQUIRED, 'The name of model');
        $this->addOption('type', null, InputOption::VALUE_OPTIONAL|InputOption::VALUE_IS_ARRAY, 'Specify action type (index, show, store, update or destroy)');
        $this->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Specify namespace (base: App\Http\Actions)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $types = $input->getOption('type');

        if (empty($types)) {
            $types = ['index', 'show', 'store', 'update', 'destroy'];
        }

        $types = array_map(fn ($type) => strtolower($type), $types);

        foreach ($types as $type) {
            list(, $class) = Make::generateClass($type, 'action', true, true);

            $class = str_replace(['Index', 'Show'], ['GetCollection', 'GetItem'], $class);

            if (!Make::createAction($input->getArgument('model'), $type, $input->getOption('namespace'))) {
                $output->writeln('<fg=yellow>Failed to create action "' . $class . '"</fg>');
            } else {
                $output->writeln('<info>Action "' . $class . '" has been created</info>');
            }
        }

        return Command::SUCCESS;
    }
}
