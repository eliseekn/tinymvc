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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new request validator.
 */
class Validator extends Command
{
    protected static $defaultName = 'make:validator';

    protected function configure(): void
    {
        $this->setDescription('Create new request validator');
        $this->addArgument('validator', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of validator (separated by space if many)');
        $this->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Specify namespace (base: App\Http\Validators)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $validators = $input->getArgument('validator');

        foreach ($validators as $validator) {
            list(, $class) = Maker::generateClass($validator, 'validator', true);

            if (! Maker::createValidator($validator, $input->getOption('namespace'))) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create request validator <options=bold>' . $class . '</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Request validator <options=bold>' . $class . '</> has been created.');
            }
        }

        return Command::SUCCESS;
    }
}
