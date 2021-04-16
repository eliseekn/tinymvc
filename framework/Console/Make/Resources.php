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
 * Create new repository file 
 */
class Resources extends Command
{
    protected static $defaultName = 'make:resources';

    protected function configure()
    {
        $this->setDescription('Create new resources');
        $this->setHelp('This command allows you to create new resources');
        $this->addArgument('resources', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of resources (separated by space if many).');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $resources = $input->getArgument('resources');

        foreach ($resources as $res) {
            Make::createController($res, 'admin');
            Make::createMigration($res);
            Make::createRepository($res);
            Make::createSeed($res);
            Make::createViews($res);
            Make::createRoute($res);

            /* list($name, $class) = Make::generateClass($repository, 'repository');

            if (!Make::createRepository($repository)) {
                $output->writeln('<fg=yellow>Failed to create repository "' . $class . '"</fg>');
            } */

            $output->writeln('<info>Resources for "' . $res . '" created succesfully</info>');
        }

        return Command::SUCCESS;
    }
}
