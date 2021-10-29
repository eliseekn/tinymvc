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
 * Create new middleware 
 */
class Middleware extends Command
{
    protected static $defaultName = 'make:middleware';

    protected function configure()
    {
        $this->setDescription('Create new middleware');
        $this->addArgument('middleware', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of middleware (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $middlewares = $input->getArgument('middleware');

        foreach ($middlewares as $middleware) {
            list(, $class) = Make::generateClass($middleware, '', true);

            if (!Make::createMiddleware($middleware)) {
                $output->writeln('<fg=yellow>Failed to create middleware "' . $class . '"</fg>');
            }

            $output->writeln('<info>Middleware "' . $class . '" has been created</info>');
        }

        return Command::SUCCESS;
    }
}
