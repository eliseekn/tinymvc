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
 * Create new test.
 */
class Test extends Command
{
    protected static $defaultName = 'make:test';

    protected function configure(): void
    {
        $this->setDescription('Create new test case');
        $this->addArgument('test', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of test (separated by space if many)');
        $this->addOption('unit', 'u', InputOption::VALUE_NONE, 'Setup for unit test');
        $this->addOption('browser', 'b', InputOption::VALUE_NONE, 'Setup for browser test');
        $this->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Specify namespace');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tests = $input->getArgument('test');

        foreach ($tests as $test) {
            list(, $class) = Maker::generateClass($test, 'test', true);

            if ($input->getOption('unit')) {
                $result = Maker::createUnitTest($test, $input->getOption('namespace'));
            } elseif ($input->getOption('browser')) {
                $result = Maker::createBrowserTest($test, $input->getOption('namespace'));
            } else {
                $result = Maker::createTest($test, $input->getOption('namespace'));
            }

            if (! $result) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create test <options=bold>' . $class . '</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Test <options=bold>' . $class . '</> has been created.');
            }
        }

        return Command::SUCCESS;
    }
}
