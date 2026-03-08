<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

/*
 * Console commands
 */

return [
    /*
     * System console commands
    */
    'core' => [
        new \Core\Console\Database\Create,
        new \Core\Console\Database\Delete,
        new \Core\Console\Database\Query,
        new \Core\Console\Database\Migrations\Delete,
        new \Core\Console\Database\Migrations\Status,
        new \Core\Console\Database\Migrations\Run,
        new \Core\Console\Database\Migrations\Reset,
        new \Core\Console\Database\Seed,

        new \Core\Console\Make\Migration,
        new \Core\Console\Make\Model,
        new \Core\Console\Make\Controller,
        new \Core\Console\Make\Validator,
        new \Core\Console\Make\Rule,
        new \Core\Console\Make\Seeder,
        new \Core\Console\Make\Factory,
        new \Core\Console\Make\View,
        new \Core\Console\Make\Notification,
        new \Core\Console\Make\Middleware,
        new \Core\Console\Make\Console,
        new \Core\Console\Make\Password,
        new \Core\Console\Make\Hash,
        new \Core\Console\Make\Helper,
        new \Core\Console\Make\Test,
        new \Core\Console\Make\UseCase,
        new \Core\Console\Make\Exception,
        new \Core\Console\Make\Event,
        new \Core\Console\Make\Listener,
        new \Core\Console\Make\Enum,
        new \Core\Console\Make\Task,
        new \Core\Console\Make\Resource,

        new \Core\Console\App\EncryptionKey,
        new \Core\Console\App\Environnement,
        new \Core\Console\App\Config,

        new \Core\Console\ClearCache,
        new \Core\Console\Server,
        new \Core\Console\Testing,
        new \Core\Console\ClearLogs,
        new \Core\Console\Routes,
        new \Core\Console\Shell,

        new \Core\Console\Task\Run,
        new \Core\Console\Task\TaskList,
        new \Core\Console\Task\Cleanup,
        new \Core\Console\Task\Watch,
        new \Core\Console\Task\Cancel,
        new \Core\Console\Task\Load,
    ],

    /*
     * Customs console commands
     */
    'app' => [
        new \App\Console\GenerateRandomQuote,
        new \App\Console\GenerateApiDoc,
    ],
];
