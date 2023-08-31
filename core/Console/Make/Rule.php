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
 * Create new request rule
 */
class Rule extends Command
{
    protected static $defaultName = 'make:rule';

    protected function configure(): void
    {
        $this->setDescription('Create new custom validator rule');
        $this->addArgument('rule', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of rule (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rules = $input->getArgument('rule');

        foreach ($rules as $rule) {
            list(, $class) = Maker::generateClass(base_name: $rule, singular: true);

            if (!Maker::createRule($rule)) {
                $output->writeln('<error>[ERROR] Failed to create request rule "' . $class . '"</error>');
            }

            $output->writeln('<info>[INFO] Request rule "' . $class . '" has been created</info>');
        }

        return Command::SUCCESS;
    }
}
