<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Console;

use Exception;
use Framework\Database\DB;
use App\Database\Seeds\Seeder;
use Framework\Support\Storage;

/**
 * Manage migrations and seeds from command line interface
 */
class Database
{    
    /**
     * print console message
     *
     * @param  mixed $message
     * @param  mixed $exit
     * @return mixed
     */
    private static function log(string $message, bool $exit = true, $nl = PHP_EOL)
    {
        return $exit ? exit($message . $nl) : print($message . $nl);
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
    
    public static function createSchema(string ...$databases)
    {
        foreach ($databases as $database) {
            try {
                DB::connection()->query("CREATE DATABASE IF NOT EXISTS $database CHARACTER SET " . config('mysql.charset') . " COLLATE " . config('mysql.collation'));
                self::log('[+] Database "' . $database . '" created successfully' . PHP_EOL, false, '');
            } catch(Exception $e) {
                self::log('[!] ' . $e->getMessage() . PHP_EOL, false, '');
            }
        }
    }

    public static function deleteSchema(string ...$databases)
    {
        foreach ($databases as $database) {
            try {
                DB::connection()->query("DROP DATABASE IF EXISTS $database");
                self::log('[+] Database "' .$database . '" deleted successfully' . PHP_EOL, false, '');
            } catch(Exception $e) {
                self::log('[!] ' . $e->getMessage() . PHP_EOL, false, '');
            }
        }
    }

    public static function executeQuery(string $query, ?string $db = null)
    {
        try {
            DB::connection($db)->query($query);
        } catch(Exception $e) {
            self::log('[!] ' . $e->getMessage() . PHP_EOL, false);
        }

        self::log('[+] Query executed successfully' . PHP_EOL, false);
    }

    public static function migrateTable(string $table = '')
    {
        if (!empty($table)) {
            if (strpos($table, ',') === false) {
                $table = 'App\Database\Migrations\\' . $table;
                
                try {
                    $table::migrate();
                    self::log('[+] Table "' . $table . '" migrated successfully' . PHP_EOL, false);
                } catch(Exception $e) {
                    self::log('[!] ' . $e->getMessage() . PHP_EOL, false);
                }
            } else {
                $tables = explode(',', $table);
    
                foreach ($tables as $table) {
                    $table = 'App\Database\Migrations\\' . $table;
                    
                    try {
                        $table::migrate();
                        self::log('[+] Table "' . $table . '" migrated successfully' . PHP_EOL, false, '');
                    } catch(Exception $e) {
                        self::log('[!] ' . $e->getMessage() . PHP_EOL, false, '');
                    }
                }
            }
        } else {
            foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                $table = get_file_name($file);
                $table = 'App\Database\Migrations\\' . $table;
                
                try {
                    $table::migrate();
                    self::log('[+] Table "' . $table . '" migrated successfully' . PHP_EOL, false, '');
                } catch(Exception $e) {
                    self::log('[!] ' . $e->getMessage() . PHP_EOL, false, '');
                }
            }
        }
    }

    public static function resetTable(string $table = '')
    {
        if (!empty($table)) {
            if (strpos($table, ',') === false) {
                $table = 'App\Database\Migrations\\' . $table;
                
                try {
                    $table::reset();
                    self::log('[+] Table "' . $table . '" resetted successfully' . PHP_EOL, false);
                } catch(Exception $e) {
                    self::log('[!] ' . $e->getMessage() . PHP_EOL, false);
                }
            } else {
                $tables = explode(',', $table);
    
                foreach ($tables as $table) {
                    $table = 'App\Database\Migrations\\' . $table;
                    
                    try {
                        $table::reset();
                        self::log('[+] Table "' . $table . '" resetted successfully' . PHP_EOL, false, '');
                    } catch(Exception $e) {
                        self::log('[!] ' . $e->getMessage() . PHP_EOL, false, '');
                    }
                }
            }
        } else {
            foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                $table = get_file_name($file);
                $table = 'App\Database\Migrations\\' . $table;
                
                try {
                    $table::reset();
                    self::log('[+] Table "' . $table . '" resetted successfully' . PHP_EOL, false, '');
                } catch(Exception $e) {
                    self::log('[!] ' . $e->getMessage() . PHP_EOL, false, '');
                }
            }
        }
    }

    public static function deleteTable(string $table = '')
    {
        if (!empty($table)) {
            if (strpos($table, ',') === false) {
                $table = 'App\Database\Migrations\\' . $table;
                
                try {
                    $table::delete();
                    self::log('[+] Table "' . $table . '" deleted successfully' . PHP_EOL, false);
                } catch(Exception $e) {
                    self::log('[!] ' . $e->getMessage() . PHP_EOL, false);
                }
            } else {
                $tables = explode(',', $table);
    
                foreach ($tables as $table) {
                    $table = 'App\Database\Migrations\\' . $table;
                    
                    try {
                        $table::delete();
                        self::log('[+] Table "' . $table . '" deleted successfully' . PHP_EOL, false, '');
                    } catch(Exception $e) {
                        self::log('[!] ' . $e->getMessage() . PHP_EOL, false, '');
                    }
                }
            }
        } else {
            foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                $table = get_file_name($file);
                $table = 'App\Database\Migrations\\' . $table;
                
                try {
                    $table::delete();
                    self::log('[+] Table "' . $table . '" deleted successfully' . PHP_EOL, false, '');
                } catch(Exception $e) {
                    self::log('[!] ' . $e->getMessage() . PHP_EOL, false, '');
                }
            }
        }
    }

    public static function runSeeder(string $seed = '')
    {
        if (!empty($seed)) {
            if (strpos($seed, ',') === false) {
                $seed = self::getSeed($seed);
                
                try {
                    $seed::insert();
                    self::log('[+] Seed "' . $seed . '" inserted successfully' . PHP_EOL, false);
                } catch(Exception $e) {
                    self::log('[!] ' . $e->getMessage() . PHP_EOL, false);
                }
            } else {
                $seeds = explode(',', $seed);
    
                foreach ($seeds as $seed) {
                    $seed = self::getSeed($seed);
                    
                    try {
                        $seed::insert();
                        self::log('[+] Seed "' . $seed . '" inserted successfully' . PHP_EOL, false, '');
                    } catch(Exception $e) {
                        self::log('[!] ' . $e->getMessage() . PHP_EOL, false, '');
                    }
                }
            }
        } else {
            try {
                Seeder::run();
                self::log('[+] All seeds inserted successfully' . PHP_EOL, false, '');
            } catch(Exception $e) {
                self::log('[!] ' . $e->getMessage() . PHP_EOL, false, '');
            }
        }
    }

    public static function getMigrationsTables(): array
    {
        $tables = [];

        foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
            $tables[] = get_file_name($file);
        }

        return $tables;
    }
}
