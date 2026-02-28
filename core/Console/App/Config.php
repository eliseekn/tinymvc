<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\App;

use Core\Cache\Cache;
use Core\Database\Connection\Connection;
use Core\Task\Task;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Show application configuration.
 */
class Config extends Command
{
    protected function configure(): void
    {
        $this->setName('app:config');
        $this->setDescription('Show application configuration');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rows = [
            ['TinyMVC version', \Composer\InstalledVersions::getPrettyVersion('eliseekn/tinymvc')],
            ['PHP version', PHP_VERSION],
            new TableSeparator,
            ['App name', config('app.name')],
            ['Base URL', config('app.url')],
            ['Environnement', config('app.env')],
            ['Language', config('app.lang')],
            ['Errors display', config('errors.display') ? '<fg=green>Yes</>' : '<fg=red>No</>'],
            new TableSeparator,
            ['Database driver', Connection::getDriver()],
            ['Cache driver', Cache::getDriver()],
            ['Task driver', Task::getDriver()],
            ['Errors logging', config('errors.log') ? '<fg=green>Yes</>' : '<fg=red>No</>'],
            new TableSeparator,
            ['Cache encryption', config('security.encryption.cache') ? '<fg=green>Yes</>' : '<fg=red>No</>'],
            ['Cookies encryption', config('security.encryption.cookies') ? '<fg=green>Yes</>' : '<fg=red>No</>'],
            ['Session lifetime', config('security.session.lifetime').'s'],
            new TableSeparator,
            ['Auth identifier', config('security.auth.identifier')],
            ['Email verification', config('security.auth.email_verification') ? '<fg=green>Yes</>' : '<fg=red>No</>'],
        ];

        $table = new Table($output);
        $table->setHeaders(['Name', 'Value']);
        $table->setRows($rows);
        $table->render();

        return Command::SUCCESS;
    }
}
