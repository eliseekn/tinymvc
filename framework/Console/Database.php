<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
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
        $table = ucfirst($table) . 'Table';
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
        if ($seed[-1] === 's') {
            $seed = rtrim($seed, 's');
        }

        $seed = ucfirst($seed) . 'Seed';
        return 'App\Database\Seeds\\' . $seed;
    }
    
    /**
     * handle command line arguments
     *
     * @param  array $options
     * @return void
     */
    public static function handle(array $options): void
    {
        if (
            array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('delete', $options) &&
            !array_key_exists('reset', $options) &&
            !array_key_exists('schema', $options) &&
            !array_key_exists('query', $options) &&
            !array_key_exists('fetch', $options) &&
            !array_key_exists('execute', $options)
        ) {
            echo '[+] Migrating tables...' . PHP_EOL;

            if ($options['migration'] !== 'all') {
                $table = $options['migration'];
        
                if (strpos($table, ',') === false) {
                    $table = self::getMigration($table);
                    
                    try {
                        $table::migrate();
                        echo '[+] ' . $table . ' migrated successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        echo '[!] ' . $e->getMessage();
                    }
                } else {
                    $tables = explode(',', $table);
        
                    foreach ($tables as $table) {
                        $table = self::getMigration($table);
                        
                        try {
                            $table::migrate();
                            echo '[+] ' . $table . ' migrated successfully' . PHP_EOL;
                        } catch(Exception $e) {
                            echo '[!] ' . $e->getMessage();
                        }
                    }
                }
            } else {
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = explode('.', $file)[0];
                    $table = 'App\Database\Migrations\\' . $table;
                    
                    try {
                        $table::migrate();
                        echo '[+] ' . $table . ' migrated successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        echo '[!] ' . $e->getMessage();
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
            echo '[+] Migrating tables...' . PHP_EOL;

            if ($options['migration'] !== 'all') {
                $table = $options['migration'];
        
                if (strpos($table, ',') === false) {
                    $table = self::getMigration($table);
                    
                    try {
                        $table::migrate();
                        echo '[+] ' . $table . ' migrated successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        echo '[!] ' . $e->getMessage();
                    }
                } else {
                    $tables = explode(',', $table);
        
                    foreach ($tables as $table) {
                        $table = self::getMigration($table);
                        
                        try {
                            $table::migrate();
                            echo '[+] ' . $table . ' migrated successfully' . PHP_EOL;
                        } catch(Exception $e) {
                            echo '[!] ' . $e->getMessage();
                        }
                    }
                }
            } else {
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = explode('.', $file)[0];
                    $table = 'App\Database\Migrations\\' . $table;
                    
                    try {
                        $table::migrate();
                        echo '[+] ' . $table . ' migrated successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        echo '[!] ' . $e->getMessage();
                    }
                }
            }
        
            echo '[+] Inserting seeds...' . PHP_EOL;

            if ($options['seed'] !== 'all') {
                $seed = $options['seed'];
        
                if (strpos($seed, ',') === false) {
                    $seed = self::getSeed($seed);
                    
                    try {
                        $seed::insert();
                        echo '[+] ' . $seed . ' inserted successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        echo '[!] ' . $e->getMessage();
                    }
                } else {
                    $seeds = explode(',', $seed);
        
                    foreach ($seeds as $seed) {
                        $seed = self::getSeed($seed);
                        
                        try {
                            $seed::insert();
                            echo '[+] ' . $seed . ' inserted successfully' . PHP_EOL;
                        } catch(Exception $e) {
                            echo '[!] ' . $e->getMessage();
                        }
                    }
                }
            } else {
                foreach (Storage::path(config('storage.seeds'))->getFiles() as $file) {
                    $seed = explode('.', $file)[0];
                    $seed = 'App\Database\Seeds\\' . $seed;
                    
                    try {
                        $seed::insert();
                        echo '[+] ' . $seed . ' inserted successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        echo '[!] ' . $e->getMessage();
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
            echo '[+] Reseting tables...' . PHP_EOL;

            if ($options['migration'] !== 'all') {
                $table = $options['migration'];
        
                if (strpos($table, ',') === false) {
                    $table = self::getMigration($table);

                    try {
                        $table::reset();
                        echo '[+] ' . $table . ' resetted successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        echo '[!] ' . $e->getMessage();
                    }
                } else {
                    $tables = explode(',', $table);
        
                    foreach ($tables as $table) {
                        $table = self::getMigration($table);
                        
                        try {
                            $table::reset();
                            echo '[+] ' . $table . ' resetted successfully' . PHP_EOL;
                        } catch(Exception $e) {
                            echo '[!] ' . $e->getMessage();
                        }
                    }
                }
            } else {
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = explode('.', $file)[0];
                    $table = 'App\Database\Migrations\\' . $table;
                    
                    try {
                        $table::reset();
                        echo '[+] ' . $table . ' resetted successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        echo '[!] ' . $e->getMessage();
                    }
                }
            }
        
            echo '[+] Inserting seeds...' . PHP_EOL;

            if ($options['seed'] !== 'all') {
                $seed = $options['seed'];
        
                if (strpos($seed, ',') === false) {
                    $seed = self::getSeed($seed);

                    try {
                        $seed::insert();
                        echo '[+] ' . $seed . ' inserted successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        echo '[!] ' . $e->getMessage();
                    }
                } else {
                    $seeds = explode(',', $seed);
        
                    foreach ($seeds as $seed) {
                        $seed = self::getSeed($seed);
                        
                        try {
                            $seed::insert();
                            echo '[+] ' . $seed . ' inserted successfully' . PHP_EOL;
                        } catch(Exception $e) {
                            echo '[!] ' . $e->getMessage();
                        }
                    }
                }
            } else {
                foreach (Storage::path(config('storage.seeds'))->getFiles() as $file) {
                    $seed = explode('.', $file)[0];
                    $seed = 'App\Database\Seeds\\' . $seed;

                    try {
                        $seed::insert();
                        echo '[+] ' . $seed . ' inserted successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        echo '[!] ' . $e->getMessage();
                    }
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
            echo '[+] Reseting tables...' . PHP_EOL;

            if ($options['migration'] !== 'all') {
                $table = $options['migration'];
        
                if (strpos($table, ',') === false) {
                    $table = self::getMigration($table);
                    
                    try {
                        $table::reset();
                        echo '[+] ' . $table . ' resetted successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        echo '[!] ' . $e->getMessage();
                    }
                } else {
                    $tables = explode(',', $table);
        
                    foreach ($tables as $table) {
                        $table = self::getMigration($table);
                        
                        try {
                            $table::reset();
                            echo '[+] ' . $table . ' resetted successfully' . PHP_EOL;
                        } catch(Exception $e) {
                            echo '[!] ' . $e->getMessage();
                        }
                    }
                }
            } else {
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = explode('.', $file)[0];
                    $table = 'App\Database\Migrations\\' . $table;
                    
                    try {
                        $table::reset();
                        echo '[+] ' . $table . ' resetted successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        echo '[!] ' . $e->getMessage();
                    }
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
            echo '[+] Deleting tables...' . PHP_EOL;

            if ($options['migration'] !== 'all') {
                $table = $options['migration'];
        
                if (strpos($table, ',') === false) {
                    $table = self::getMigration($table);
                    
                    try {
                        $table::reset();
                        echo '[+] ' . $table . ' deleted successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        echo '[!] ' . $e->getMessage();
                    }
                } else {
                    $tables = explode(',', $table);
        
                    foreach ($tables as $table) {
                        $table = self::getMigration($table);
                        
                        try {
                            $table::reset();
                            echo '[+] ' . $table . ' deleted successfully' . PHP_EOL;
                        } catch(Exception $e) {
                            echo '[!] ' . $e->getMessage();
                        }
                    }
                }
            } else {
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = explode('.', $file)[0];
                    $table = 'App\Database\Migrations\\' . $table;
                    
                    try {
                        $table::reset();
                        echo '[+] ' . $table . ' deleted successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        echo '[!] ' . $e->getMessage();
                    }
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
            echo '[+] Inserting seeds...' . PHP_EOL;

            if ($options['seed'] !== 'all') {
                $seed = $options['seed'];
        
                if (strpos($seed, ',') === false) {
                    $seed = self::getSeed($seed);
                    
                    try {
                        $seed::insert();
                        echo '[+] ' . $seed . ' inserted successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        echo '[!] ' . $e->getMessage();
                    }
                } else {
                    $seeds = explode(',', $seed);
        
                    foreach ($seeds as $seed) {
                        $seed = self::getSeed($seed);
                        
                        try {
                            $seed::insert();
                            echo '[+] ' . $seed . ' inserted successfully' . PHP_EOL;
                        } catch(Exception $e) {
                            echo '[!] ' . $e->getMessage();
                        }
                    }
                }
            } else {
                foreach (Storage::path(config('storage.seeds'))->getFiles() as $file) {
                    $seed = explode('.', $file)[0];
                    $seed = 'App\Database\Seeds\\' . $seed;
                    
                    try {
                        $seed::insert();
                        echo '[+] ' . $seed . ' inserted successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        echo '[!] ' . $e->getMessage();
                    }
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
            echo '[+] Creating databases...' . PHP_EOL;

            if (strpos($options['schema'], ',') === false) {
                $database = $options['schema'];
                
                try {
                    DB::getInstance()->executeQuery("CREATE DATABASE IF NOT EXISTS $database CHARACTER SET " . config('db.charset') . " COLLATE " . config('db.collation'));
                    echo '[+] ' . $database . ' created successfully' . PHP_EOL;
                } catch(Exception $e) {
                    echo '[!] ' . $e->getMessage();
                }
            } else {
                $db = explode(',', $options['schema']);

                foreach ($db as $database) {
                    try {
                        DB::getInstance()->executeQuery("CREATE DATABASE IF NOT EXISTS $database CHARACTER SET " . config('db.charset') . " COLLATE " . config('db.collation'));
                        echo '[+] ' . $database . ' created successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        echo '[!] ' . $e->getMessage();
                    }
                }
            }
        }

        else if (
            array_key_exists('schema', $options) &&
            array_key_exists('delete', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('reset', $options) &&
            !array_key_exists('query', $options) &&
            !array_key_exists('fetch', $options) &&
            !array_key_exists('execute', $options)
        ) {
            echo '[+] Deleting databases...' . PHP_EOL;

            if (strpos($options['schema'], ',') === false) {
                $database = $options['schema'];
                
                try {
                    DB::getInstance()->executeQuery("DROP DATABASE IF EXISTS $database");
                    echo '[+] ' .$database . ' deleted successfully' . PHP_EOL;
                } catch(Exception $e) {
                    echo '[!] ' . $e->getMessage();
                }
            } else {
                $db = explode(',', $options['schema']);

                foreach ($db as $database) {
                    try {
                        DB::getInstance()->executeQuery("DROP DATABASE IF EXISTS $database");
                        echo '[+] ' .$database . ' deleted successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        echo '[!] ' . $e->getMessage();
                    }
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
            echo '[+] Executing MySQL query...' . PHP_EOL;

            try {
                $stmt = DB::getInstance()->executeQuery($options['query']);
            } catch(Exception $e) {
                echo '[!] ' . $e->getMessage();
            }

            print_r($stmt->fetchAll());
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
            echo '[+] Executing MySQL query...' . PHP_EOL;

            try {
                DB::getInstance()->executeQuery($options['query']);
            } catch(Exception $e) {
                echo '[!] ' . $e->getMessage();
            }
        }
        
        exit('[+] All operations done' . PHP_EOL);
    }
}
