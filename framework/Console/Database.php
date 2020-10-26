<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Console;

use Framework\ORM\Builder;
use Framework\Support\Storage;
use Framework\ORM\Database as DB;

/**
 * Manage migrations and seeds from command line interface
 */
class Database
{
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
     * @param  array $options
     * @return void
     */
    public static function parseCommands(array $options): void
    {
        if (
            array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('refresh', $options) &&
            !array_key_exists('db', $options)
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
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = explode('.', $file)[0];
                    $table = self::checkMigration($table);
                    $table::migrate();
                }
            }
        }
        
        else if (
            array_key_exists('migration', $options) &&
            array_key_exists('seed', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('refresh', $options) &&
            !array_key_exists('db', $options)
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
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = explode('.', $file)[0];
                    $table = self::checkMigration($table);
                    $table::migrate();
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
                foreach (Storage::path(config('storage.seeds'))->getFiles() as $file) {
                    $seed = explode('.', $file)[0];
                    $seed = self::checkSeed($seed);
                    $seed::insert();
                }
            }
        } 
        
        else if (
            array_key_exists('migration', $options) &&
            array_key_exists('seed', $options) &&
            array_key_exists('refresh', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('db', $options)
        ) {
            if ($options['migration'] !== 'all') {
                $table = $options['migration'];
        
                if (strpos($table, ',') === false) {
                    $table = self::checkMigration($table);
                    $table::refresh();
                } else {
                    $tables = explode(',', $table);
        
                    foreach ($tables as $table) {
                        $table = self::checkMigration($table);
                        $table::refresh();
                    }
                }
            } else {
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = explode('.', $file)[0];
                    $table = self::checkMigration($table);
                    $table::refresh();
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
                foreach (Storage::path(config('storage.seeds'))->getFiles() as $file) {
                    $seed = explode('.', $file)[0];
                    $seed = self::checkSeed($seed);
                    $seed::insert();
                }
            }
        } 
        
        else if (
            array_key_exists('migration', $options) &&
            array_key_exists('refresh', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('db', $options)
        ) {
            if ($options['migration'] !== 'all') {
                $table = $options['migration'];
        
                if (strpos($table, ',') === false) {
                    $table = self::checkMigration($table);
                    $table::refresh();
                } else {
                    $tables = explode(',', $table);
        
                    foreach ($tables as $table) {
                        $table = self::checkMigration($table);
                        $table::refresh();
                    }
                }
            } else {
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = explode('.', $file)[0];
                    $table = self::checkMigration($table);
                    $table::refresh();
                }
            }
        } 
        
        else if (
            array_key_exists('migration', $options) &&
            array_key_exists('delete', $options) &&
            !array_key_exists('refresh', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('db', $options)
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
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = explode('.', $file)[0];
                    $table = self::checkMigration($table);
                    $table::delete();
                }
            }
        } 
        
        else if (
            array_key_exists('seed', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('refresh', $options) &&
            !array_key_exists('db', $options)
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
                foreach (Storage::path(config('storage.seeds'))->getFiles() as $file) {
                    $seed = explode('.', $file)[0];
                    $seed = self::checkSeed($seed);
                    $seed::insert();
                }
            }
        }
        
        else if (
            array_key_exists('db', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('refresh', $options)
        ) {
            if (strpos($options['db'], ',') === false) {
                $database = $options['db'];
                DB::getInstance()->setQuery("CREATE DATABASE $database CHARACTER SET " . config('database.charset') . " COLLATE " . config('database.collation'));
            } else {
                $db = explode(',', $options['db']);

                foreach ($db as $database) {
                    DB::getInstance()->setQuery("CREATE DATABASE $database CHARACTER SET " . config('database.charset') . " COLLATE " . config('database.collation'));
                }
            }
        }

        else if (
            array_key_exists('db', $options) &&
            array_key_exists('delete', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('refresh', $options)
        ) {
            if (strpos($options['db'], ',') === false) {
                $database = $options['db'];
                Builder::query("DROP DATABASE IF EXISTS $database")->execute();
            } else {
                $db = explode(',', $options['db']);

                foreach ($db as $database) {
                    Builder::query("DROP DATABASE IF EXISTS $database")->execute();
                }
            }
        }
        
        else if (
            array_key_exists('db', $options) &&
            array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('refresh', $options)
        ) {
            if (strpos($options['db'], ',') === false) {
                $database = $options['db'];
                Builder::query("DROP DATABASE IF EXISTS $database")->execute();
            } else {
                $db = explode(',', $options['db']);

                foreach ($db as $database) {
                    Builder::query("DROP DATABASE IF EXISTS $database")->execute();
                }
            }

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
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = explode('.', $file)[0];
                    $table = self::checkMigration($table);
                    $table::migrate();
                }
            }
        }
        
        else if (
            array_key_exists('db', $options) &&
            array_key_exists('migration', $options) &&
            array_key_exists('seed', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('refresh', $options)
        ) {
            if (strpos($options['db'], ',') === false) {
                $database = $options['db'];
                DB::getInstance()->setQuery("CREATE DATABASE $database CHARACTER SET " . config('database.charset') . " COLLATE " . config('database.collation'));
            } else {
                $db = explode(',', $options['db']);

                foreach ($db as $database) {
                    DB::getInstance()->setQuery("CREATE DATABASE $database CHARACTER SET " . config('database.charset') . " COLLATE " . config('database.collation'));
                }
            }

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
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = explode('.', $file)[0];
                    $table = self::checkMigration($table);
                    $table::migrate();
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
                foreach (Storage::path(config('storage.seeds'))->getFiles() as $file) {
                    $seed = explode('.', $file)[0];
                    $seed = self::checkSeed($seed);
                    $seed::insert();
                }
            }
        }
        
        else if (array_key_exists('help', $options)) {
            $help_message = '[+] Commands list:' . PHP_EOL;
            $help_message .= PHP_EOL;
            $help_message .= '      --db=users                                  Create users database with utf8 encoding character' . PHP_EOL;
            $help_message .= '      --db=users,comments                         Create users and comments databases' . PHP_EOL;
            $help_message .= '      --db=users --delete                         Delete users database' . PHP_EOL;
            $help_message .= '      --db=users,comments --delete                Delete users and comments databases' . PHP_EOL;
            $help_message .= '      --db=users --migration=all                  Create users database and migrate all tables (use default database configuration)' . PHP_EOL;
            $help_message .= '      --db=users --migration=all --seed=all       Create users database, migrate all tables and insert all seeds (use default database configuration)' . PHP_EOL;
            $help_message .= PHP_EOL;
            $help_message .= '      --migration=all                             Migrate all tables' . PHP_EOL;
            $help_message .= '      --migration=UsersTable                      Migrate UsersTable only' . PHP_EOL;
            $help_message .= '      --migration=UsersTable,CommentsTable        Migrate UsersTable and CommentsTable only' . PHP_EOL;
            $help_message .= '      --migration=UsersTable,PostsTable --delete  Drop UsersTable and PostsTable only' . PHP_EOL;
            $help_message .= '      --migration=all --delete                    Drop all tables' . PHP_EOL;
            $help_message .= PHP_EOL;
            $help_message .= '      --seed=all                                  Insert all seeds' . PHP_EOL;
            $help_message .= '      --seed=UserSeed                             Insert UserSeed only' . PHP_EOL;
            $help_message .= '      --seed=UserSeed,CommentSeed                 Insert UserSeed and CommentSeed only' . PHP_EOL;
            $help_message .= PHP_EOL;
            $help_message .= '      --migration=all --seed=all                  Migrate all tables and insert seeds' . PHP_EOL;
            $help_message .= '      --migration=all --refresh                   Refresh all tables migration' . PHP_EOL;
            $help_message .= '      --migration=all --refresh --seed=all        Refresh all tables migration and insert seeds' . PHP_EOL;
            
            exit($help_message);
        } 
        
        else {
            exit('[-] Invalid command line arguments, print "--help" for commands list' . PHP_EOL);
        }
        
        exit('[+] Operations done successfully.' . PHP_EOL);
    }
}
