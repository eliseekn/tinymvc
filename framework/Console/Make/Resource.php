<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Console\Make;

use Framework\Console\Make;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new admin resource 
 */
class Resource extends Command
{
    protected static $defaultName = 'make:resource';

    protected function configure()
    {
        $this->setDescription('Create new admin resource');
        $this->setHelp('This command allows you to create new admin resource');
        $this->addArgument('resource', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of resource (separated by space if many)');
        $this->addOption('views', null, InputOption::VALUE_NONE, 'Create views only');
        $this->addOption('routes', null, InputOption::VALUE_NONE, 'Create routes only');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $resources = $input->getArgument('resource');

        if ($input->getOption('views')) {
            foreach ($resources as $resource) {
                if (!Make::createViews($resource)) {
                    $output->writeln('<fg=yellow>Failed to create views for "' . $resource . '"</fg>');
                }

                $output->writeln('<info>Views for "' . $resource . '" created succesfully</info>');
            }
        }

        else if ($input->getOption('routes')) {
            foreach ($resources as $resource) {
                if (!Make::createRoute($resource)) {
                    $output->writeln('<fg=yellow>Failed to create routes for "' . $resource . '"</fg>');
                }

                $output->writeln('<info>Routes for "' . $resource . '" created succesfully</info>');
            }
        }
        
        else {
            foreach ($resources as $resource) {
                //controller
                list($name, $class) = Make::generateClass($resource, 'controller');
    
                if (!Make::createController($resource, 'admin')) {
                    $output->writeln('<fg=yellow>Failed to create controller "' . $class . '"</fg>');
                }
    
                $output->writeln('<info>Controller "' . $class . '" created succesfully</info>');
    
                //migration
                list($name, $class) = Make::generateClass($resource, 'migration');
    
                if (!Make::createMigration($resource)) {
                    $output->writeln('<fg=yellow>Failed to create migration "' . $class . '"</fg>');
                }
    
                $output->writeln('<info>Migration "' . $class . '" created succesfully</info>');
    
                //repository
                list($name, $class) = Make::generateClass($resource, 'repository');
    
                if (!Make::createRepository($resource)) {
                    $output->writeln('<fg=yellow>Failed to create repository "' . $class . '"</fg>');
                }
    
                $output->writeln('<info>Repository "' . $class . '" created succesfully</info>');
    
                //seed
                list($name, $class) = Make::generateClass($resource, 'seed');
    
                if (!Make::createSeed($resource)) {
                    $output->writeln('<fg=yellow>Failed to create seed "' . $class . '"</fg>');
                }
    
                $output->writeln('<info>Seed "' . $class . '" created succesfully</info>');
    
                //views
                if (!Make::createViews($resource)) {
                    $output->writeln('<fg=yellow>Failed to create views for "' . $resource . '"</fg>');
                }
    
                $output->writeln('<info>Views for "' . $resource . '" created succesfully</info>');
    
                //route
                if (!Make::createRoute($resource)) {
                    $output->writeln('<fg=yellow>Failed to create route for "' . $resource . '"</fg>');
                }
    
                $output->writeln('<info>Routes for "' . $resource . '" created succesfully</info>');
            }
                
            $output->writeln('<info>CRUD resources for "' . $resource . '" created succesfully</info>');
        }

        return Command::SUCCESS;
    }
}
