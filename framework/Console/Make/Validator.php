<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Console\Make;

use Framework\Console\Make;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new request validator
 */
class Validator extends Command
{
    protected static $defaultName = 'make:validator';

    protected function configure()
    {
        $this->setDescription('Create new request validator');
        $this->setHelp('This command allows you to create new request validator');
        $this->addArgument('validator', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of validator (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $validators = $input->getArgument('validator');

        foreach ($validators as $validator) {
            list($name, $class) = Make::generateClass($validator, '');

            if (!Make::createValidator($validator)) {
                $output->writeln('<fg=yellow>Failed to create request validator "' . $class . '"</fg>');
            }

            $output->writeln('<info>Request validator "' . $class . '" has been created</info>');
        }

        return Command::SUCCESS;
    }
}
