<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Console;

use Exception;
use Framework\System\Storage;
use App\Database\Seeds\Seeder;
use Framework\Database\Schema;
use Framework\Database\QueryBuilder;
use Framework\Database\Database as DB;

/**
 * Manage migrations and seeds from command line interface
 */
class Database
{    
    /**
     * print console message
     *
     * @param  string $message
     * @param  string $type
     * @return mixed
     */
    private static function log(string $message, string $type = 'success')
    {
        if ($type === 'warning') {
            echo "\e[0;33;40m{$message}\e[0m\n";
        } else if ($type === 'error') {
            echo "\e[1;37;41m{$message}\e[0m\n";
        } else {
            echo "\e[0;32;40m{$message}\e[0m\n";
        }
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
            if (QueryBuilder::schemaExists($database)) {
                self::log('[!] Database "' . $database . '" exists already', 'warning');
            } else {
                try {
                    DB::connection()->query("CREATE DATABASE $database CHARACTER SET " . config('mysql.charset') . " COLLATE " . config('mysql.collation'));
                    self::log('[+] Database "' . $database . '" created successfully');
                } catch(Exception $e) {
                    self::log('[!] ' . $e->getMessage(), 'error');
                }
            }
        }
    }
    
    /**
     * delete database
     *
     * @param  string[] $databases
     * @return void
     */
    public static function dropSchema(string ...$databases): void
    {
        foreach ($databases as $database) {
            if (QueryBuilder::schemaExists($database)) {
                self::log('[!] Database "' . $database . '" does not exists', 'warning');
            } else {
                try {
                    DB::connection()->query("DROP DATABASE IF EXISTS $database");
                    self::log('[+] Database "' .$database . '" deleted successfully');
                } catch(Exception $e) {
                    self::log('[!] ' . $e->getMessage(), 'error');
                }
            }
        }
    }
    
    /**
     * listSchemas
     *
     * @return void
     */
    public static function listSchemas(): void
    {
        $databases = DB::connection()->query("SHOW DATABASES")->fetchAll();

        foreach ($databases as $db) {
            echo "[+] " . $db->Database . "\n";
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
            self::log('[!] ' . $e->getMessage(), 'error');
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
                    self::log('[!] Table "' . $table . '" already migrated', 'warning');
                } else {
                    self::saveMigrationTable($table);

                    $table = 'App\Database\Migrations\\' . $table;
                
                    try {
                        $table::migrate();
                        self::log('[+] Table "' . $table . '" migrated successfully');
                    } catch(Exception $e) {
                        self::log('[!] ' . $e->getMessage(), 'error');
                    }
                }
            } else {
                $tables = explode(',', $table);
    
                foreach ($tables as $table) {
                    if (self::alreadyMigrated($table)) {
                        self::log('[!] Table "' . $table . '" already migrated', 'warning');
                    } else {
                        self::saveMigrationTable($table);
    
                        $table = 'App\Database\Migrations\\' . $table;
                        
                        try {
                            $table::migrate();
                            self::saveMigrationTable($table);
                            self::log('[+] Table "' . $table . '" migrated successfully');
                        } catch(Exception $e) {
                            self::log('[!] ' . $e->getMessage(), 'error');
                        }
                    }
                }
            }
        } else {
            foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                $table = get_file_name($file);
                
                if (self::alreadyMigrated($table)) {
                    self::log('[!] Table "' . $table . '" already migrated', 'warning');
                } else {
                    self::saveMigrationTable($table);

                    $table = 'App\Database\Migrations\\' . $table;

                    try {
                        $table::migrate();
                        self::log('[+] Table "' . $table . '" migrated successfully');
                    } catch(Exception $e) {
                        self::log('[!] ' . $e->getMessage(), 'error');
                    }
                }
            }
        }
    }

    /**
     * refresh table migration
     *
     * @param  string $table
     * @return void
     */
    public static function refreshTable(string $table = ''): void
    {
        if (!empty($table)) {
            if (strpos($table, ',') === false) {
                $table = 'App\Database\Migrations\\' . $table;
                
                try {
                    $table::refresh();
                    self::log('[+] Table "' . $table . '" refreshed successfully');
                } catch(Exception $e) {
                    self::log('[!] ' . $e->getMessage(), 'error');
                }
            } else {
                $tables = explode(',', $table);
    
                foreach ($tables as $table) {
                    $table = 'App\Database\Migrations\\' . $table;
                    
                    try {
                        $table::refresh();
                        self::log('[+] Table "' . $table . '" refreshed successfully');
                    } catch(Exception $e) {
                        self::log('[!] ' . $e->getMessage(), 'error');
                    }
                }
            }
        } else {
            foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                $table = get_file_name($file);
                $table = 'App\Database\Migrations\\' . $table;
                
                try {
                    $table::refresh();
                    self::log('[+] Table "' . $table . '" refreshed successfully');
                } catch(Exception $e) {
                    self::log('[!] ' . $e->getMessage(), 'error');
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
                    self::log('[!] ' . $e->getMessage(), 'error');
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
                        self::log('[!] ' . $e->getMessage(), 'error');
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
                    self::log('[!] ' . $e->getMessage(), 'error');
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
                    self::log('[!] ' . $e->getMessage(), 'error');
                }
            } else {
                $seeds = explode(',', $seed);
    
                foreach ($seeds as $seed) {
                    $seed = self::getSeed($seed);
                    
                    try {
                        $seed::insert();
                        self::log('[+] Seed "' . $seed . '" inserted successfully');
                    } catch(Exception $e) {
                        self::log('[!] ' . $e->getMessage(), 'error');
                    }
                }
            }
        } else {
            try {
                Seeder::run();
                self::log('[+] All seeds inserted successfully');
            } catch(Exception $e) {
                self::log('[!] ' . $e->getMessage(), 'error');
            }
        }
    }
    
    /**
     * retrieves migrations tables list
     *
     * @return void
     */
    public static function getMigrationsTables(): void
    {
        $tables = Storage::path(config('storage.migrations'))->getFiles();

        foreach ($tables as $key => $table) {
            echo "[+] " . get_file_name($table) . "\n";
        }
    }
    
    /**
     * store migration table to database
     *
     * @param  string $table
     * @return void
     */
    private static function saveMigrationTable(string $table): void
    {
        if (!QueryBuilder::tableExists('migrations')) {
            Schema::createTable('migrations')
                ->addInt('id')->primaryKey()
                ->addString('migration')
                ->create();
        }

        if (!self::alreadyMigrated($table)) {
            QueryBuilder::table('migrations')->insert(['migration' => $table])->execute();
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
        QueryBuilder::table('migrations')->delete()
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
        if (!QueryBuilder::tableExists('migrations')) {
            return false;
        }

        return QueryBuilder::table('migrations')
            ->select('*')
            ->where('migration', $table)
            ->exists();
    }
}
