<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console;

use Core\Enums\AppEnv;
use Core\Support\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * Run PHPUnit tests cases.
 */
class Testing extends Command
{
    protected static $defaultName = 'test';

    protected function configure(): void
    {
        $this->setDescription('Run tests cases');
        $this->addArgument('test', InputArgument::OPTIONAL, 'Specify test name');
        $this->addArgument('filter', InputArgument::OPTIONAL, 'Specify test case');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $appUrl = config('app.url');

        Config::updateEnv([
            'APP_ENV' => AppEnv::TEST,
            'APP_URL' => config('testing.url.protocol').config('testing.url.host').':'.config('testing.url.port').'/',
        ]);

        $server = new Process(['php', '-S', config('testing.url.host').':'.config('testing.url.port')]);
        $server->setTimeout(null);
        $server->start();

        $args = ['php', 'vendor/bin/phpunit'];

        if (! is_null($input->getArgument('test'))) {
            $filename = str_contains($input->getArgument('test'), '.php')
                ? $input->getArgument('test')
                : $input->getArgument('test').'.php';

            $args = array_merge($args, ['tests'.DIRECTORY_SEPARATOR.$filename]);
        }

        if (! is_null($input->getArgument('filter'))) {
            $args = array_merge($args, ['--filter='.$input->getArgument('filter')]);
        }

        $phpunit = new Process($args, null, [
            'PANTHER_NO_HEADLESS' => ! config('testing.browser.headless') ? '1' : '0',
            'PANTHER_ERROR_SCREENSHOT_DIR' => config('testing.browser.screenshots_dir'),
        ]);
        $phpunit->setTimeout(null);
        $phpunit->start();
        $phpunit->wait(function ($type, $buffer) use ($output) {
            $lines = explode("\n", trim($buffer));

            foreach ($lines as $line) {
                $line = trim($line);

                if (preg_match('/^OK \(\d+ tests?, \d+ assertions?\)$/', $line)) {
                    $output->writeln('<bg=green;fg=black> '.$line.'</>');
                } elseif ($line === 'ERRORS!' || $line === 'FAILURES!') {
                    $output->writeln('<bg=red;fg=black> '.$line.'</>');
                } elseif (in_array($line, ['.', 'F', 'E'])) {
                    $output->write($line);
                } else {
                    $output->writeln($line);
                    $output->write('');
                }
            }
        });

        $server->stop();

        Config::updateEnv([
            'APP_ENV' => AppEnv::LOCAL,
            'APP_URL' => $appUrl,
        ]);

        return Command::SUCCESS;
    }
}
