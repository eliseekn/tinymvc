<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * Start a local server development.
 */
class Server extends Command
{
    protected static $defaultName = 'serve';

    protected function configure(): void
    {
        $this->setDescription('Start a local server development');
        $this->addOption('host', null, InputOption::VALUE_OPTIONAL, 'Specify server host');
        $this->addOption('port', null, InputOption::VALUE_OPTIONAL, 'Specify server port');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $host = $input->getOption('host') ?? parse_url(config('app.url'), PHP_URL_HOST);
        $port = $input->getOption('port') ?? parse_url(config('app.url'), PHP_URL_PORT);

        $process = new Process(['php', '-S', "$host:$port"]);
        $process->setTimeout(null);
        $process->start();

        $process->wait(function ($type, $buffer) use ($output) {
            $output->write($buffer);
        });

        return Command::SUCCESS;
    }
}
