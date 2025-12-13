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
 * Create new middleware.
 */
class Middleware extends Command
{
    protected function configure(): void
    {
        $this->setName('make:middleware');
        $this->setDescription('Create new middleware');
        $this->addArgument('middleware', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of middleware (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $middlewares = $input->getArgument('middleware');

        foreach ($middlewares as $middleware) {
            [, $class] = Maker::generateClass($middleware, singular: true);

            if (! $this->createMiddleware($middleware)) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create middleware <options=bold>'.$class.'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Middleware <options=bold>'.$class.'</> has been created.');
            }
        }

        return Command::SUCCESS;
    }

    public function createMiddleware(string $middleware): bool
    {
        [, $class] = Maker::generateClass($middleware, singular: true);

        $data = Maker::stubs()->readFile('Middleware.stub');
        $data = str_replace('CLASSNAME', $class, $data);

        return storage(config('storage.middlewares'))->writeFile($class.'.php', $data);
    }
}
