<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Console;

/**
 * CLI
 * 
 * Manage migrations and seeds from command line interface
 */
class CLI
{    
    /**
     * migrations folder
     *
     * @var string
     */
    protected static $migrations = 'app' . DIRECTORY_SEPARATOR . 'Database' . DIRECTORY_SEPARATOR . 'Migrations';
    
    /**
     * seeds folder
     *
     * @var string
     */
    protected static $seeds = 'app' . DIRECTORY_SEPARATOR . 'Database' . DIRECTORY_SEPARATOR . 'Seeds';
    
    /**
     * print help message
     *
     * @return void
     */
    private static function helpMessage(): void
    {
        $help_message = '[+] Commands list:' . PHP_EOL;
        $help_message .= '  --migration=all                             Migrate all tables' . PHP_EOL;
        $help_message .= '  --migration=UsersTable                      Migrate UsersTable only' . PHP_EOL;
        $help_message .= '  --migration=UsersTable,CommentsTable        Migrate UsersTable and CommentsTable only' . PHP_EOL;
        $help_message .= '  --migration=UsersTable,PostsTable --delete  Drop UsersTable and PostsTable only' . PHP_EOL;
        $help_message .= '  --migration=all --delete                    Drop all tables' . PHP_EOL;
        $help_message .= '  --seed=all                                  Insert all seeds' . PHP_EOL;
        $help_message .= '  --seed=UserSeed                             Insert UserSeed only' . PHP_EOL;
        $help_message .= '  --seed=UserSeed,CommentSeed                 Insert UserSeed and CommentSeed only' . PHP_EOL;
        $help_message .= '  --migration=all --seed=all                  Migrate all tables and insert all seeds' . PHP_EOL;
        $help_message .= '  --migration=all --rollback                  Revert migrates for all tables' . PHP_EOL;
        $help_message .= '  --migration=all --rollback --seed=all       Revert migrates for all tables and insert all seeds' . PHP_EOL;
        
        exit($help_message);
    }
    
    /**
     * check migration class
     *
     * @param  string $table
     * @return mixed
     */
    private static function checkMigration(string $table)
    {
        $migration = 'App\Database\Migrations\\' . $table;

        if (!class_exists($migration) || !method_exists($migration, 'migrate')) {
            exit('[-] Invalid migration class "' . $migration . '"' . PHP_EOL);
        }

        return $migration;
    }
    
    /**
     * check seed class
     *
     * @param  string $seed
     * @return mixed
     */
    private static function checkSeed(string $seed)
    {
        $seeder = 'App\Database\Seeds\\' . $seed;

        if (!class_exists($seeder) || !method_exists($seeder, 'insert')) {
            exit('[-] Invalid seeder class "' . $seeder . '"' . PHP_EOL);
        }

        return $seeder;
    }
    
    /**
     * parseCommands
     *
     * @param  mixed $options
     * @return void
     */
    public static function parseCommands(array $options)
    {
        if (
            array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('rollback', $options)
        ) {
            if ($options['migration'] !== 'all') {
                $table = $options['migration'];
        
                if (strpos($table, ',') === false) {
                    $table = self::checkMigration($table);
                    $table::migrate();
                } else {
                    $tables = explode(',', $table);
        
                    foreach ($tables as $table) {
                        $table = self::checkMigration($table);
                        $table::migrate();
                    }
                }
            } else {
                $objects = scandir(self::$migrations);
        
                foreach ($objects as $object) {
                    if ($object != '.' && $object != '..') {
                        $table = explode('.', basename($object))[0];
                        $table = self::checkMigration($table);
                        $table::migrate();
                    }
                }
            }
        
            exit('[+] Operations done successfully.' . PHP_EOL);
        } 
        
        else if (
            array_key_exists('migration', $options) &&
            array_key_exists('seed', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('rollback', $options)
        ) {
            if ($options['migration'] !== 'all') {
                $table = $options['migration'];
        
                if (strpos($table, ',') === false) {
                    $table = self::checkMigration($table);
                    $table::migrate();
                } else {
                    $tables = explode(',', $table);
        
                    foreach ($tables as $table) {
                        $table = self::checkMigration($table);
                        $table::migrate();
                    }
                }
            } else {
                $objects = scandir(self::$migrations);
        
                foreach ($objects as $object) {
                    if ($object != '.' && $object != '..') {
                        $table = explode('.', basename($object))[0];
                        $table = self::checkMigration($table);
                        $table::migrate();
                    }
                }
            }
        
            if ($options['seed'] !== 'all') {
                $seed = $options['seed'];
        
                if (strpos($seed, ',') === false) {
                    $seed = self::checkSeed($seed);
                    $seed::insert();
                } else {
                    $seeds = explode(',', $seed);
        
                    foreach ($seeds as $seed) {
                        $seed = self::checkSeed($seed);
                        $seed::insert();
                    }
                }
            } else {
                $objects = scandir(self::$seeds);
        
                foreach ($objects as $object) {
                    if ($object != '.' && $object != '..') {
                        $seed = explode('.', basename($object))[0];
                        $seed = self::checkSeed($seed);
                        $seed::insert();
                    }
                }
            }
        
            exit('[+] Operations done successfully.' . PHP_EOL);
        } 
        
        else if (
            array_key_exists('migration', $options) &&
            array_key_exists('seed', $options) &&
            array_key_exists('rollback', $options) &&
            !array_key_exists('delete', $options)
        ) {
            if ($options['migration'] !== 'all') {
                $table = $options['migration'];
        
                if (strpos($table, ',') === false) {
                    $table = self::checkMigration($table);
                    $table::rollback();
                } else {
                    $tables = explode(',', $table);
        
                    foreach ($tables as $table) {
                        $table = self::checkMigration($table);
                        $table::rollback();
                    }
                }
            } else {
                $objects = scandir(self::$migrations);
        
                foreach ($objects as $object) {
                    if ($object != '.' && $object != '..') {
                        $table = explode('.', basename($object))[0];
                        $table = self::checkMigration($table);
                        $table::rollback();
                    }
                }
            }
        
            if ($options['seed'] !== 'all') {
                $seed = $options['seed'];
        
                if (strpos($seed, ',') === false) {
                    $seed = self::checkSeed($seed);
                    $seed::insert();
                } else {
                    $seeds = explode(',', $seed);
        
                    foreach ($seeds as $seed) {
                        $seed = self::checkSeed($seed);
                        $seed::insert();
                    }
                }
            } else {
                $objects = scandir(self::$seeds);
        
                foreach ($objects as $object) {
                    if ($object != '.' && $object != '..') {
                        $seed = explode('.', basename($object))[0];
                        $seed = self::checkSeed($seed);
                        $seed::insert();
                    }
                }
            }
        
            exit('[+] Operations done successfully.' . PHP_EOL);
        } 
        
        else if (
            array_key_exists('migration', $options) &&
            array_key_exists('rollback', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('delete', $options)
        ) {
            if ($options['migration'] !== 'all') {
                $table = $options['migration'];
        
                if (strpos($table, ',') === false) {
                    $table = self::checkMigration($table);
                    $table::rollback();
                } else {
                    $tables = explode(',', $table);
        
                    foreach ($tables as $table) {
                        $table = self::checkMigration($table);
                        $table::rollback();
                    }
                }
            } else {
                $objects = scandir(self::$migrations);
        
                foreach ($objects as $object) {
                    if ($object != '.' && $object != '..') {
                        $table = explode('.', basename($object))[0];
                        $table = self::checkMigration($table);
                        $table::rollback();
                    }
                }
            }
        
            exit('[+] Operations done successfully.' . PHP_EOL);
        } 
        
        else if (
            array_key_exists('migration', $options) &&
            array_key_exists('delete', $options) &&
            !array_key_exists('rollback', $options) &&
            !array_key_exists('seed', $options)
        ) {
            if ($options['migration'] !== 'all') {
                $table = $options['migration'];
        
                if (strpos($table, ',') === false) {
                    $table = self::checkMigration($table);
                    $table::delete();
                } else {
                    $tables = explode(',', $table);
        
                    foreach ($tables as $table) {
                        $table = self::checkMigration($table);
                        $table::delete();
                    }
                }
            } else {
                $objects = scandir(self::$migrations);
        
                foreach ($objects as $object) {
                    if ($object != '.' && $object != '..') {
                        $table = explode('.', basename($object))[0];
                        $table = self::checkMigration($table);
                        $table::delete();
                    }
                }
            }
        
            exit('[+] Operations done successfully.' . PHP_EOL);
        } 
        
        else if (
            array_key_exists('seed', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('rollback', $options)
        ) {
            if ($options['seed'] !== 'all') {
                $seed = $options['seed'];
        
                if (strpos($seed, ',') === false) {
                    $seed = self::checkSeed($seed);
                    $seed::insert();
                } else {
                    $seeds = explode(',', $seed);
        
                    foreach ($seeds as $seed) {
                        $seed = self::checkSeed($seed);
                        $seed::insert();
                    }
                }
            } else {
                $objects = scandir(self::$seeds);
        
                foreach ($objects as $object) {
                    if ($object != '.' && $object != '..') {
                        $seed = explode('.', basename($object))[0];
                        $seed = self::checkSeed($seed);
                        $seed::insert();
                    }
                }
            }
        
            exit('[+] Operations done successfully.' . PHP_EOL);
        } 
        
        else if (array_key_exists('help', $options)) {
            self::helpMessage();
        } 
        
        else {
            exit('[-] Invalid command line arguments, print "--help" for commands list' . PHP_EOL);
        }
    }
}