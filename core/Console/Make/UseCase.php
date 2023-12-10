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
 * Create new use cases
 */
class UseCase extends Command
{
    protected static $defaultName = 'make:use-case';

    protected function configure(): void
    {
        $this->setDescription('Create new use case');
        $this->addArgument('model', InputArgument::REQUIRED, 'The name of model');
        $this->addOption('type', null, InputOption::VALUE_OPTIONAL|InputOption::VALUE_IS_ARRAY, 'Specify use case type (index, show, store, update or destroy)');
        $this->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Specify namespace (base: App\Http\UseCases)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $types = $input->getOption('type');

        if (empty($types)) {
            $types = ['index', 'show', 'store', 'update', 'destroy'];
        }

        $types = array_map(fn ($type) => strtolower($type), $types);

        foreach ($types as $type) {
            list(, $class) = Maker::generateClass($type, 'use_case', true, true);
            $class = str_replace(['Index', 'Show'], ['GetCollection', 'GetItem'], $class);

            if (!Maker::createUseCase($input->getArgument('model'), $type, $output, $input->getOption('namespace'))) {
                $output->writeln('<error>[ERROR] Failed to create use case "' . $class . '"</error>');
            } else {
                $output->writeln('<info>[INFO] Use case "' . $class . '" has been created</info>');
            }
        }

        return Command::SUCCESS;
    }
}
