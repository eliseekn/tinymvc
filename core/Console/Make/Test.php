<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new test 
 */
class Test extends Command
{
    protected static $defaultName = 'make:test';

    protected function configure()
    {
        $this->setDescription('Create new test');
        $this->addArgument('test', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of test (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tests = $input->getArgument('test');

        foreach ($tests as $test) {
            list($name, $class) = Make::generateClass($test, 'test', true);

            if (!Make::createTest($test)) {
                $output->writeln('<fg=yellow>Failed to create test "' . $class . '"</fg>');
            }

            $output->writeln('<info>Test "' . $class . '" has been created</info>');
        }

        return Command::SUCCESS;
    }
}
