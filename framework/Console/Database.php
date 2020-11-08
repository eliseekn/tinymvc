<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Console;

use Exception;
use Framework\Support\Storage;
use Framework\ORM\Database as DB;

/**
 * Manage migrations and seeds from command line interface
 */
class Database
{
    /**
     * get migration class
     *
     * @param  string $table
     * @return string
     */
    private static function getMigration(string $table): string
    {
        return 'App\Database\Migrations\\' . $table;
    }
    
    /**
     * get seed class
     *
     * @param  string $seed
     * @return string
     */
    private static function getSeed(string $seed): string
    {
        return 'App\Database\Seeds\\' . $seed;
    }
    
    /**
     * parse cli
     *
     * @param  array $options
     * @return void
     */
    public static function parseCLI(array $options): void
    {
        if (
            array_key_exists('migration', $options) &&
            !array_keys_exists('seed', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('refresh', $options) &&
            !array_key_exists('db', $options) &&
            !array_key_exists('query', $options) &&
            !array_key_exists('fetch', $options) &&
            !array_key_exists('execute', $options)
        ) {
            echo '[+] Running migrations...' . PHP_EOL;

            if ($options['migration'] !== 'all') {
                $table = $options['migration'];
        
                if (strpos($table, ',') === false) {
                    $table = self::getMigration($table);
                    
                    try {
                        $table::migrate();
                        echo '[+] ' . $table . ' migrated successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        exit('[-] ' . $e->getMessage());
                    }
                } else {
                    $tables = explode(',', $table);
        
                    foreach ($tables as $table) {
                        $table = self::getMigration($table);
                        
                        try {
                            $table::migrate();
                            echo '[+] ' . $table . ' migrated successfully' . PHP_EOL;
                        } catch(Exception $e) {
                            exit('[-] ' . $e->getMessage());
                        }
                    }
                }
            } else {
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = explode('.', $file)[0];
                    $table = self::getMigration($table);
                    
                    try {
                        $table::migrate();
                        echo '[+] ' . $table . ' migrated successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        exit('[-] ' . $e->getMessage());
                    }
                }
            }
        }
        
        else if (
            array_key_exists('migration', $options) &&
            array_key_exists('seed', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('refresh', $options) &&
            !array_key_exists('db', $options) &&
            !array_key_exists('query', $options) &&
            !array_key_exists('fetch', $options) &&
            !array_key_exists('execute', $options)
        ) {
            echo '[+] Running migrations...' . PHP_EOL;

            if ($options['migration'] !== 'all') {
                $table = $options['migration'];
        
                if (strpos($table, ',') === false) {
                    $table = self::getMigration($table);
                    
                    try {
                        $table::migrate();
                        echo '[+] ' . $table . ' migrated successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        exit('[-] ' . $e->getMessage());
                    }
                } else {
                    $tables = explode(',', $table);
        
                    foreach ($tables as $table) {
                        $table = self::getMigration($table);
                        
                        try {
                            $table::migrate();
                            echo '[+] ' . $table . ' migrated successfully' . PHP_EOL;
                        } catch(Exception $e) {
                            exit('[-] ' . $e->getMessage());
                        }
                    }
                }
            } else {
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = explode('.', $file)[0];
                    $table = self::getMigration($table);
                    
                    try {
                        $table::migrate();
                        echo '[+] ' . $table . ' migrated successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        exit('[-] ' . $e->getMessage());
                    }
                }
            }
        
            echo '[+] Inserting seeds...' . PHP_EOL;

            if ($options['seed'] !== 'all') {
                $seed = $options['seed'];
        
                if (strpos($seed, ',') === false) {
                    $seed = self::getSeed($seed);
                    
                    try {
                        $seed::migrate();
                        echo '[+] ' . $seed . ' inserted successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        exit('[-] ' . $e->getMessage());
                    }
                } else {
                    $seeds = explode(',', $seed);
        
                    foreach ($seeds as $seed) {
                        $seed = self::getSeed($seed);
                        
                        try {
                            $seed::migrate();
                            echo '[+] ' . $seed . ' inserted successfully' . PHP_EOL;
                        } catch(Exception $e) {
                            exit('[-] ' . $e->getMessage());
                        }
                    }
                }
            } else {
                foreach (Storage::path(config('storage.seeds'))->getFiles() as $file) {
                    $seed = explode('.', $file)[0];
                    $seed = self::getSeed($seed);
                    
                    try {
                        $seed::migrate();
                        echo '[+] ' . $seed . ' inserted successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        exit('[-] ' . $e->getMessage());
                    }
                }
            }
        } 
        
        else if (
            array_key_exists('migration', $options) &&
            array_key_exists('seed', $options) &&
            array_key_exists('refresh', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('db', $options) &&
            !array_key_exists('query', $options) &&
            !array_key_exists('fetch', $options) &&
            !array_key_exists('execute', $options)
        ) {
            if ($options['migration'] !== 'all') {
                $table = $options['migration'];
        
                if (strpos($table, ',') === false) {
                    $table = self::getMigration($table);
                    $table::refresh();
                } else {
                    $tables = explode(',', $table);
        
                    foreach ($tables as $table) {
                        $table = self::getMigration($table);
                        $table::refresh();
                    }
                }
            } else {
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = explode('.', $file)[0];
                    $table = self::getMigration($table);
                    $table::refresh();
                }
            }
        
            if ($options['seed'] !== 'all') {
                $seed = $options['seed'];
        
                if (strpos($seed, ',') === false) {
                    $seed = self::getSeed($seed);
                    $seed::insert();
                } else {
                    $seeds = explode(',', $seed);
        
                    foreach ($seeds as $seed) {
                        $seed = self::getSeed($seed);
                        $seed::insert();
                    }
                }
            } else {
                foreach (Storage::path(config('storage.seeds'))->getFiles() as $file) {
                    $seed = explode('.', $file)[0];
                    $seed = self::getSeed($seed);
                    $seed::insert();
                }
            }
        } 
        
        else if (
            array_key_exists('migration', $options) &&
            array_key_exists('refresh', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('db', $options) &&
            !array_key_exists('query', $options) &&
            !array_key_exists('fetch', $options) &&
            !array_key_exists('execute', $options)
        ) {
            if ($options['migration'] !== 'all') {
                $table = $options['migration'];
        
                if (strpos($table, ',') === false) {
                    $table = self::getMigration($table);
                    $table::refresh();
                } else {
                    $tables = explode(',', $table);
        
                    foreach ($tables as $table) {
                        $table = self::getMigration($table);
                        $table::refresh();
                    }
                }
            } else {
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = explode('.', $file)[0];
                    $table = self::getMigration($table);
                    $table::refresh();
                }
            }
        } 
        
        else if (
            array_key_exists('migration', $options) &&
            array_key_exists('delete', $options) &&
            !array_key_exists('refresh', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('db', $options) &&
            !array_key_exists('query', $options) &&
            !array_key_exists('fetch', $options) &&
            !array_key_exists('execute', $options)
        ) {
            if ($options['migration'] !== 'all') {
                $table = $options['migration'];
        
                if (strpos($table, ',') === false) {
                    $table = self::getMigration($table);
                    $table::delete();
                } else {
                    $tables = explode(',', $table);
        
                    foreach ($tables as $table) {
                        $table = self::getMigration($table);
                        $table::delete();
                    }
                }
            } else {
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = explode('.', $file)[0];
                    $table = self::getMigration($table);
                    $table::delete();
                }
            }
        } 
        
        else if (
            array_key_exists('seed', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('refresh', $options) &&
            !array_key_exists('db', $options) &&
            !array_key_exists('query', $options) &&
            !array_key_exists('fetch', $options) &&
            !array_key_exists('execute', $options)
        ) {
            if ($options['seed'] !== 'all') {
                $seed = $options['seed'];
        
                if (strpos($seed, ',') === false) {
                    $seed = self::getSeed($seed);
                    $seed::insert();
                } else {
                    $seeds = explode(',', $seed);
        
                    foreach ($seeds as $seed) {
                        $seed = self::getSeed($seed);
                        $seed::insert();
                    }
                }
            } else {
                foreach (Storage::path(config('storage.seeds'))->getFiles() as $file) {
                    $seed = explode('.', $file)[0];
                    $seed = self::getSeed($seed);
                    $seed::insert();
                }
            }
        }
        
        else if (
            array_key_exists('db', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('refresh', $options) &&
            !array_key_exists('query', $options) &&
            !array_key_exists('fetch', $options) &&
            !array_key_exists('execute', $options)
        ) {
            if (strpos($options['db'], ',') === false) {
                $database = $options['db'];
                DB::getInstance()->executeQuery("CREATE DATABASE $database CHARACTER SET " . config('db.charset') . " COLLATE " . config('db.collation'));
            } else {
                $db = explode(',', $options['db']);

                foreach ($db as $database) {
                    DB::getInstance()->executeQuery("CREATE DATABASE $database CHARACTER SET " . config('db.charset') . " COLLATE " . config('db.collation'));
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
                DB::getInstance()->executeQuery("DROP DATABASE IF EXISTS $database");
            } else {
                $db = explode(',', $options['db']);

                foreach ($db as $database) {
                    DB::getInstance()->executeQuery("DROP DATABASE IF EXISTS $database");
                }
            }
        }
        
        else if (
            array_key_exists('query', $options) &&
            array_key_exists('fetch', $options) &&
            !array_key_exists('execute', $options) &&
            !array_key_exists('db', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('refresh', $options)
        ) {
            $stmt = DB::getInstance()->executeQuery($options['query']);

            if ($options['fetch'] === 'single') {
                print_r($stmt->fetch());
            } else if ($options['fetch'] === 'all') {
                print_r($stmt->fetchAll());
            } else {
                exit('[-] Invalid fetch option');
            }
        }

        else if (
            array_key_exists('query', $options) &&
            array_key_exists('execute', $options) &&
            !array_key_exists('fetch', $options) &&
            !array_key_exists('db', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('refresh', $options)
        ) {
            DB::getInstance()->executeQuery($options['query']);
        }
        
        else if (array_key_exists('help', $options)) {
            $help_message = '[+] Commands list:' . PHP_EOL;
            $help_message .= PHP_EOL;
            $help_message .= '      --db=users                                  Create users database with utf8 encoding character' . PHP_EOL;
            $help_message .= '      --db=users,comments                         Create users and comments databases' . PHP_EOL;
            $help_message .= '      --db=users --delete                         Delete users database' . PHP_EOL;
            $help_message .= '      --db=users,comments --delete                Delete users and comments databases' . PHP_EOL;
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
            $help_message .= PHP_EOL;
            $help_message .= "      --query='sql query' --fetch=single|all      Execute SQL query and fetch results (single or all)" . PHP_EOL;
            $help_message .= "      --query='sql query' --execute               Execute SQL query only" . PHP_EOL;
            
            exit($help_message);
        } 
        
        else {
            exit('[-] Invalid command line arguments, print "--help" for commands list' . PHP_EOL);
        }
        
        exit('[+] Operations done successfully' . PHP_EOL);
    }
}
