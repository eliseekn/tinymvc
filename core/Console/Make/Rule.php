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
 * Create new request rule.
 */
class Rule extends Command
{
    protected static $defaultName = 'make:rule';

    protected function configure(): void
    {
        $this->setDescription('Create new custom validator rule');
        $this->addArgument('rule', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of rule (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rules = $input->getArgument('rule');

        foreach ($rules as $rule) {
            [, $class] = Maker::generateClass($rule, singular: true);

            if (! Maker::createRule($rule)) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create request rule <options=bold>'.$class.'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Request rule <options=bold>'.$class.'</> has been created.');
            }
        }

        return Command::SUCCESS;
    }
}
