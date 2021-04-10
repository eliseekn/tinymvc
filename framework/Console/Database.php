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
use Framework\Database\Builder;
use Framework\Database\Schema;
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
     * @return mixed
     */
    private static function log(string $message)
    {
        return print($message . PHP_EOL);
    }

    /**
     * get seed class
     *
     * @param  string $seed
     * @return string
     */
    private static function getSeed(string $seed): string
    {
        $seed = ucfirst($seed) . 'Seed';
        return 'App\Database\Seeds\\' . $seed;
    }
        
    /**
     * create database
     *
     * @param  string[] $databases
     * @return void
     */
    public static function createSchema(string ...$databases): void
    {
        foreach ($databases as $database) {
            try {
                DB::connection()->query("CREATE DATABASE IF NOT EXISTS $database CHARACTER SET " . config('mysql.charset') . " COLLATE " . config('mysql.collation'));
                self::log('[+] Database "' . $database . '" created successfully');
            } catch(Exception $e) {
                self::log('[!] ' . $e->getMessage());
            }
        }
    }
    
    /**
     * delete database
     *
     * @param  string[] $databases
     * @return void
     */
    public static function deleteSchema(string ...$databases): void
    {
        foreach ($databases as $database) {
            try {
                DB::connection()->query("DROP DATABASE IF EXISTS $database");
                self::log('[+] Database "' .$database . '" deleted successfully');
            } catch(Exception $e) {
                self::log('[!] ' . $e->getMessage());
            }
        }
    }
    
    /**
     * execute SQL query
     *
     * @param  string $query
     * @param  string|null $db
     * @return void
     */
    public static function executeQuery(string $query, ?string $db = null): void
    {
        try {
            DB::connection($db)->query($query);
        } catch(Exception $e) {
            self::log('[!] ' . $e->getMessage());
        }

        self::log('[+] Query executed successfully');
    }
    
    /**
     * migrate table
     *
     * @param  string $table
     * @return void
     */
    public static function migrateTable(string $table = ''): void
    {
        if (!empty($table)) {
            if (strpos($table, ',') === false) {
                if (self::alreadyMigrated($table)) {
                    self::log('[!] Table "' . $table . '" already migrated');
                } else {
                    self::saveMigrationTable($table);

                    $table = 'App\Database\Migrations\\' . $table;
                
                    try {
                        $table::migrate();
                        self::log('[+] Table "' . $table . '" migrated successfully');
                    } catch(Exception $e) {
                        self::log('[!] ' . $e->getMessage());
                    }
                }
            } else {
                $tables = explode(',', $table);
    
                foreach ($tables as $table) {
                    if (self::alreadyMigrated($table)) {
                        self::log('[!] Table "' . $table . '" already migrated');
                    } else {
                        self::saveMigrationTable($table);
    
                        $table = 'App\Database\Migrations\\' . $table;
                        
                        try {
                            $table::migrate();
                            self::saveMigrationTable($table);
                            self::log('[+] Table "' . $table . '" migrated successfully');
                        } catch(Exception $e) {
                            self::log('[!] ' . $e->getMessage());
                        }
                    }
                }
            }
        } else {
            foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                $table = get_file_name($file);
                
                if (self::alreadyMigrated($table)) {
                    self::log('[!] Table "' . $table . '" already migrated');
                } else {
                    self::saveMigrationTable($table);

                    $table = 'App\Database\Migrations\\' . $table;

                    try {
                        $table::migrate();
                        self::log('[+] Table "' . $table . '" migrated successfully');
                    } catch(Exception $e) {
                        self::log('[!] ' . $e->getMessage());
                    }
                }
            }
        }
    }

    /**
     * reset table migration
     *
     * @param  string $table
     * @return void
     */
    public static function resetTable(string $table = ''): void
    {
        if (!empty($table)) {
            if (strpos($table, ',') === false) {
                $table = 'App\Database\Migrations\\' . $table;
                
                try {
                    $table::reset();
                    self::log('[+] Table "' . $table . '" resetted successfully');
                } catch(Exception $e) {
                    self::log('[!] ' . $e->getMessage());
                }
            } else {
                $tables = explode(',', $table);
    
                foreach ($tables as $table) {
                    $table = 'App\Database\Migrations\\' . $table;
                    
                    try {
                        $table::reset();
                        self::log('[+] Table "' . $table . '" resetted successfully');
                    } catch(Exception $e) {
                        self::log('[!] ' . $e->getMessage());
                    }
                }
            }
        } else {
            foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                $table = get_file_name($file);
                $table = 'App\Database\Migrations\\' . $table;
                
                try {
                    $table::reset();
                    self::log('[+] Table "' . $table . '" resetted successfully');
                } catch(Exception $e) {
                    self::log('[!] ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * delete table migration
     *
     * @param  string $table
     * @return void
     */
    public static function deleteTable(string $table = ''): void
    {
        if (!empty($table)) {
            if (strpos($table, ',') === false) {
                self::removeMigrationTable($table);
                $table = 'App\Database\Migrations\\' . $table;
                
                try {
                    $table::delete();
                    self::log('[+] Table "' . $table . '" deleted successfully');
                } catch(Exception $e) {
                    self::log('[!] ' . $e->getMessage());
                }
            } else {
                $tables = explode(',', $table);
    
                foreach ($tables as $table) {
                    self::removeMigrationTable($table);
                    $table = 'App\Database\Migrations\\' . $table;
                    
                    try {
                        $table::delete();
                        self::log('[+] Table "' . $table . '" deleted successfully');
                    } catch(Exception $e) {
                        self::log('[!] ' . $e->getMessage());
                    }
                }
            }
        } else {
            foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                $table = get_file_name($file);
                self::removeMigrationTable($table);

                $table = 'App\Database\Migrations\\' . $table;
                
                try {
                    $table::delete();
                    self::log('[+] Table "' . $table . '" deleted successfully');
                } catch(Exception $e) {
                    self::log('[!] ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * run seed
     *
     * @param  string $seed
     * @return void
     */
    public static function runSeeder(string $seed = ''): void
    {
        if (!empty($seed)) {
            if (strpos($seed, ',') === false) {
                $seed = self::getSeed($seed);
                
                try {
                    $seed::insert();
                    self::log('[+] Seed "' . $seed . '" inserted successfully');
                } catch(Exception $e) {
                    self::log('[!] ' . $e->getMessage());
                }
            } else {
                $seeds = explode(',', $seed);
    
                foreach ($seeds as $seed) {
                    $seed = self::getSeed($seed);
                    
                    try {
                        $seed::insert();
                        self::log('[+] Seed "' . $seed . '" inserted successfully');
                    } catch(Exception $e) {
                        self::log('[!] ' . $e->getMessage());
                    }
                }
            }
        } else {
            try {
                Seeder::run();
                self::log('[+] All seeds inserted successfully');
            } catch(Exception $e) {
                self::log('[!] ' . $e->getMessage());
            }
        }
    }
    
    /**
     * retrieves migrations tables list
     *
     * @return array
     */
    public static function getMigrationsTables(): array
    {
        $tables = [];

        foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
            $tables[] = get_file_name($file);
        }

        return $tables;
    }
    
    /**
     * store migration table to database
     *
     * @param  string $table
     * @return void
     */
    private static function saveMigrationTable(string $table): void
    {
        if (!Builder::tableExists('migrations')) {
            Schema::createTable('migrations')
                ->addInt('id')->primaryKey()
                ->addString('migration')
                ->create();
        }

        if (!self::alreadyMigrated($table)) {
            Builder::insert('migrations', ['migration' => $table])->execute();
        }
    }
    
    /**
     * remove migration table to database
     *
     * @param  string $table
     * @return void
     */
    private static function removeMigrationTable(string $table): void
    {
        Builder::delete('migrations')
            ->where('migration', $table)
            ->execute();
    }
    
    /**
     * check if table has alredy been migrated
     *
     * @param  mixed $table
     * @return bool
     */
    public static function alreadyMigrated(string $table): bool
    {
        if (!Builder::tableExists('migrations')) {
            return false;
        }

        return Builder::select('*')
            ->from('migrations')
            ->where('migration', $table)
            ->exists();
    }
}
