<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console;

use Closure;
use Core\Http\Routing\Route;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Display registered routes list.
 */
class Routes extends Command
{
    protected function configure(): void
    {
        $this->setName('routes');
        $this->setDescription('Display registered routes list');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rows = [];
        $routes = Route::getAll();

        foreach ($routes as $route => $options) {
            list($method, $uri) = explode(' ', $route, 2);
            $handler = $options['handler'];
            $middlewares = $options['middlewares'] ?? '';
            $name = $options['name'] ?? '';

            if (is_array($handler)) {
                list($controller, $action) = $handler;
                $handler = $controller.'@'.$action;
            }

            if ($handler instanceof Closure) {
                $handler = 'Closure';
            }

            if (! empty($middlewares)) {
                $middlewares = implode(', ', $middlewares);
            }

            $rows[] = [$method, $uri, $handler, $middlewares, $name];
        }

        $table = new Table($output);
        $table->setHeaders(['Method', 'Uri', 'Handler', 'Middlewares', 'Name']);
        $table->setRows($rows);
        $table->render();

        return Command::SUCCESS;
    }
}
