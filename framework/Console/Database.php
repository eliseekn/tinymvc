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
        echo '[...] Creating databases' . PHP_EOL;

        foreach ($databases as $database) {
            try {
                DB::connection()->query("CREATE DATABASE IF NOT EXISTS $database CHARACTER SET " . config('mysql.charset') . " COLLATE " . config('mysql.collation'));
                echo '[+] Database ' . $database . ' created successfully' . PHP_EOL;
            } catch(Exception $e) {
                echo '[!] ' . $e->getMessage();
            }
        }
    }

    public static function deleteSchema(string ...$databases)
    {
        echo '[...] Deleting databases' . PHP_EOL;

        foreach ($databases as $database) {
            try {
                DB::connection()->query("DROP DATABASE IF EXISTS $database");
                echo '[+] Database ' .$database . ' deleted successfully' . PHP_EOL;
            } catch(Exception $e) {
                echo '[!] ' . $e->getMessage();
            }
        }
    }

    public static function executeQuery(string $query, ?string $db = null)
    {
        echo '[...] Executing MySQL query' . PHP_EOL;

        try {
            DB::connection($db)->query($query);
        } catch(Exception $e) {
            echo '[!] ' . $e->getMessage();
        }

        echo '[+] Query executed successfully' . PHP_EOL;
    }

    public static function migrateTable(string $table = '')
    {
        echo '[...] Migrating tables' . PHP_EOL;

        if (!empty($table)) {
            if (strpos($table, ',') === false) {
                $table = 'App\Database\Migrations\\' . $table;
                
                try {
                    $table::migrate();
                    echo '[+] ' . $table . ' migrated successfully' . PHP_EOL;
                } catch(Exception $e) {
                    echo '[!] ' . $e->getMessage();
                }
            } else {
                $tables = explode(',', $table);
    
                foreach ($tables as $table) {
                    $table = 'App\Database\Migrations\\' . $table;
                    
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
                $table = get_file_name($file);
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

    public static function resetTable(string $table = '')
    {
        echo '[...] Reseting tables' . PHP_EOL;

        if (!empty($table)) {
            if (strpos($table, ',') === false) {
                $table = 'App\Database\Migrations\\' . $table;
                
                try {
                    $table::reset();
                    echo '[+] ' . $table . ' resetted successfully' . PHP_EOL;
                } catch(Exception $e) {
                    echo '[!] ' . $e->getMessage();
                }
            } else {
                $tables = explode(',', $table);
    
                foreach ($tables as $table) {
                    $table = 'App\Database\Migrations\\' . $table;
                    
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
                $table = get_file_name($file);
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

    public static function deleteTable(string $table = '')
    {
        echo '[...] Deleting tables' . PHP_EOL;

        if (!empty($table)) {
            if (strpos($table, ',') === false) {
                $table = 'App\Database\Migrations\\' . $table;
                
                try {
                    $table::delete();
                    echo '[+] ' . $table . ' deleted successfully' . PHP_EOL;
                } catch(Exception $e) {
                    echo '[!] ' . $e->getMessage();
                }
            } else {
                $tables = explode(',', $table);
    
                foreach ($tables as $table) {
                    $table = 'App\Database\Migrations\\' . $table;
                    
                    try {
                        $table::delete();
                        echo '[+] ' . $table . ' deleted successfully' . PHP_EOL;
                    } catch(Exception $e) {
                        echo '[!] ' . $e->getMessage();
                    }
                }
            }
        } else {
            foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                $table = get_file_name($file);
                $table = 'App\Database\Migrations\\' . $table;
                
                try {
                    $table::delete();
                    echo '[+] ' . $table . ' deleted successfully' . PHP_EOL;
                } catch(Exception $e) {
                    echo '[!] ' . $e->getMessage();
                }
            }
        }
    }

    public static function runSeeder(string $seed = '')
    {
        echo '[...] Inserting seeds' . PHP_EOL;

        if (!empty($seed)) {
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
            try {
                Seeder::run();
                echo '[+] All seeds inserted successfully' . PHP_EOL;
            } catch(Exception $e) {
                echo '[!] ' . $e->getMessage();
            }
        }
    }
}
