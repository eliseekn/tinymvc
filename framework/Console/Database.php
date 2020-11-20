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
     * handle cli
     *
     * @param  array $options
     * @return void
     */
    public static function handle(array $options): void
    {
        if (
            array_key_exists('migration', $options) &&
            !array_keys_exists('seed', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('reset', $options) &&
            !array_key_exists('schema', $options) &&
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
            !array_key_exists('reset', $options) &&
            !array_key_exists('schema', $options) &&
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
            array_key_exists('reset', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('schema', $options) &&
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
                        $table::reset();
                        echo '[+] ' . $table . ' resetted successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        exit('[-] ' . $e->getMessage());
                    }
                } else {
                    $tables = explode(',', $table);
        
                    foreach ($tables as $table) {
                        $table = self::getMigration($table);
                        $table::reset();
                    }
                }
            } else {
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = explode('.', $file)[0];
                    $table = self::getMigration($table);
                    $table::reset();
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
            array_key_exists('reset', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('schema', $options) &&
            !array_key_exists('query', $options) &&
            !array_key_exists('fetch', $options) &&
            !array_key_exists('execute', $options)
        ) {
            if ($options['migration'] !== 'all') {
                $table = $options['migration'];
        
                if (strpos($table, ',') === false) {
                    $table = self::getMigration($table);
                    $table::reset();
                } else {
                    $tables = explode(',', $table);
        
                    foreach ($tables as $table) {
                        $table = self::getMigration($table);
                        $table::reset();
                    }
                }
            } else {
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = explode('.', $file)[0];
                    $table = self::getMigration($table);
                    $table::reset();
                }
            }
        } 
        
        else if (
            array_key_exists('migration', $options) &&
            array_key_exists('delete', $options) &&
            !array_key_exists('reset', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('schema', $options) &&
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
            !array_key_exists('reset', $options) &&
            !array_key_exists('schema', $options) &&
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
            array_key_exists('schema', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('reset', $options) &&
            !array_key_exists('query', $options) &&
            !array_key_exists('fetch', $options) &&
            !array_key_exists('execute', $options)
        ) {
            if (strpos($options['schema'], ',') === false) {
                $database = $options['schema'];
                DB::getInstance()->executeQuery("CREATE DATABASE $database CHARACTER SET " . config('db.charset') . " COLLATE " . config('db.collation'));
            } else {
                $db = explode(',', $options['schema']);

                foreach ($db as $database) {
                    DB::getInstance()->executeQuery("CREATE DATABASE $database CHARACTER SET " . config('db.charset') . " COLLATE " . config('db.collation'));
                }
            }
        }

        else if (
            array_key_exists('schema', $options) &&
            array_key_exists('delete', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('reset', $options)
        ) {
            if (strpos($options['schema'], ',') === false) {
                $database = $options['schema'];
                DB::getInstance()->executeQuery("DROP DATABASE IF EXISTS $database");
            } else {
                $db = explode(',', $options['schema']);

                foreach ($db as $database) {
                    DB::getInstance()->executeQuery("DROP DATABASE IF EXISTS $database");
                }
            }
        }
        
        else if (
            array_key_exists('query', $options) &&
            array_key_exists('fetch', $options) &&
            !array_key_exists('execute', $options) &&
            !array_key_exists('schema', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('reset', $options)
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
            !array_key_exists('schema', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('reset', $options)
        ) {
            DB::getInstance()->executeQuery($options['query']);
        }
        
        exit('[+] All operations done' . PHP_EOL);
    }
}
