<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
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

    protected function configure(): void
    {
        $this->setDescription('Create new middleware');
        $this->addArgument('middleware', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of middleware (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $middlewares = $input->getArgument('middleware');

        foreach ($middlewares as $middleware) {
            list(, $class) = Maker::generateClass($middleware, '', true);

            if (!Maker::createMiddleware($middleware)) {
                $output->writeln('<error>[ERROR] Failed to create middleware "' . $class . '"</error>');
            } else {
                $output->writeln('<info>[INFO] Middleware "' . $class . '" has been created</info>');
            }
        }

        return Command::SUCCESS;
    }
}
