<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

use App\Console\GenerateApiDocumentation;
use App\Console\GenerateRandomQuote;
use Core\Console\App\Config;
use Core\Console\App\EncryptionKey;
use Core\Console\App\Environnement;
use Core\Console\ClearCache;
use Core\Console\ClearLogs;
use Core\Console\Database\Create;
use Core\Console\Database\Delete;
use Core\Console\Database\Migrations\Reset;
use Core\Console\Database\Migrations\Status;
use Core\Console\Database\Query;
use Core\Console\Database\Seed;
use Core\Console\Make\Console;
use Core\Console\Make\Controller;
use Core\Console\Make\Enum;
use Core\Console\Make\Event;
use Core\Console\Make\Exception;
use Core\Console\Make\Factory;
use Core\Console\Make\Hash;
use Core\Console\Make\Helper;
use Core\Console\Make\Listener;
use Core\Console\Make\Middleware;
use Core\Console\Make\Migration;
use Core\Console\Make\Model;
use Core\Console\Make\Notification;
use Core\Console\Make\Observer;
use Core\Console\Make\Password;
use Core\Console\Make\Rule;
use Core\Console\Make\Seeder;
use Core\Console\Make\Task;
use Core\Console\Make\Test;
use Core\Console\Make\UseCase;
use Core\Console\Make\Validator;
use Core\Console\Make\View;
use Core\Console\Routes;
use Core\Console\Server;
use Core\Console\Shell;
use Core\Console\Task\Cancel;
use Core\Console\Task\Cleanup;
use Core\Console\Task\Load;
use Core\Console\Task\Run;
use Core\Console\Task\TaskList;
use Core\Console\Task\Watch;
use Core\Console\Testing;

/*
 * Console commands
 */

return [
    /*
     * System console commands
    */
    'core' => [
        new Create,
        new Delete,
        new Query,
        new Core\Console\Database\Migrations\Delete,
        new Status,
        new Core\Console\Database\Migrations\Run,
        new Reset,
        new Seed,

        new Migration,
        new Model,
        new Controller,
        new Validator,
        new Rule,
        new Seeder,
        new Factory,
        new View,
        new Notification,
        new Middleware,
        new Console,
        new Password,
        new Hash,
        new Helper,
        new Test,
        new UseCase,
        new Exception,
        new Event,
        new Listener,
        new Enum,
        new Task,
        new Core\Console\Make\Resource,
        new Observer,

        new EncryptionKey,
        new Environnement,
        new Config,

        new ClearCache,
        new Server,
        new Testing,
        new ClearLogs,
        new Routes,
        new Shell,

        new Run,
        new TaskList,
        new Cleanup,
        new Watch,
        new Cancel,
        new Load,
    ],

    /*
     * Customs console commands
     */
    'app' => [
        new GenerateRandomQuote,
        new GenerateApiDocumentation,
    ],
];
