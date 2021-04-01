<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Console;

use Framework\Support\Storage;

/**
 * Manage application stubs
 */
class Maker
{
    /**
     * get stubs path
     *
     * @return \Framework\Support\Storage
     */
    private static function stubs(): Storage
    {
        return Storage::path(config('storage.stubs'));
    }
    
    /**
     * generate resource folder
     *
     * @param  string $resource
     * @return string
     */
    private static function resourceFolder(string $resource): string
    {
        if ($resource[-1] !== 's') {
            $resource = $resource . 's';
        }

        return 'admin/' . $resource;
    }
    
    /**
     * generate resource name
     *
     * @param  string $resource
     * @return string
     */
    private static function resourceName(string $resource): string
    {
        if ($resource[-1] === 's') {
            $resource = rtrim($resource, 's');
        }

        return strtolower($resource);
    }
    
    /**
     * generate classname
     *
     * @param  string $name
     * @param  string $class
     * @return array
     */
    public static function generateClass(string $name, string $class): array
    {
        $name = strtolower($name);
        
        if ($class === 'migration') {
            $class = 'table';
        }

        if ($class === 'seed') {
            $name = rtrim($name, 's');
        }

        return [$name, ucfirst($name) . ucfirst($class)];
    }
    
    /**
     * generate resource name and folder
     *
     * @param  string $name
     * @return array
     */
    public static function generateResource(string $name): array
    {
        $resource_name = self::resourceName($name);
        $resource_folder = self::resourceFolder($resource_name);
        return [$resource_name, $resource_folder];
    }
    
    /**
     * generate routes for controller
     *
     * @param  string $controller
     * @return void
     */
    public static function makeRoute(string $controller): void
    {
        echo '[...] Generating routes' . PHP_EOL;

        list($controller_name, $controller_class) = self::generateClass($controller, 'controller');

        $data = self::stubs()->readFile('Route.stub');
        $data = str_replace('CLASSNAME', $controller_class, $data);
        $data = str_replace('RESOURCENAME', $controller_name, $data);

        if (!Storage::path(config('storage.routes'))->writeFile('admin.php', $data, true)) {
            exit('[!] Failed to generate routes for ' . $controller_class . PHP_EOL);
        }
        
        echo '[+] Routes for ' . $controller_class . ' generated successfully' . PHP_EOL;
    }
    
    /**
     * generate controller file
     *
     * @param  string $controller
     * @param  string|null $namespace
     * @return void
     */
    public static function makeController(string $controller, ?string $namespace = null): void
    {
        echo '[...] Generating controller' . PHP_EOL;

        list($controller_name, $controller_class) = self::generateClass($controller, 'controller');

        $data = self::stubs()->readFile('Controller.stub');
        $data = is_null($namespace) ? str_replace('NAMESPACE', 'App\Controllers', $data) : str_replace('NAMESPACE', 'App\Controllers\\' . ucfirst($namespace), $data);
        $data = str_replace('CLASSNAME', $controller_class, $data);
        $data = str_replace('RESOURCENAME', $controller_name, $data);
        $data = str_replace('RESOURCEMODEL', ucfirst($controller_name) . 'Model', $data);

        $path = Storage::path(config('storage.controllers'));

        if (!is_null($namespace)) {
            $path->add(ucfirst($namespace));
        }

        if (!$path->writeFile($controller_class . '.php', $data)) {
            exit('[!] Failed to generate controller ' . $controller_class . PHP_EOL);
        }
        
        echo '[+] Controller ' . $controller_class . ' generated successfully' . PHP_EOL;
    }
    
    /**
     * generate views file
     *
     * @param  string $resource
     * @return void
     */
    public static function makeViews(string $resource): void
    {
        echo '[...] Generating views' . PHP_EOL;

        list($resource_name, $resource_folder) = self::generateResource($resource);

        foreach(self::stubs()->add('views')->getFiles() as $file) {
            $data = self::stubs()->add('views')->readFile($file);
            $data = str_replace('RESOURCENAME', $resource_name, $data);
            $file = str_replace('stub', 'php', $file);

            if (!Storage::path(config('storage.views'))->add('admin/' . $resource_folder)->writeFile($file, $data)) {
                exit('[-] Failed to generate views for ' . $resource . PHP_EOL);
            }
        }

        echo '[+] Views for ' . $resource . ' generated successfully' . PHP_EOL;
    }

    /**
     * generate model file
     *
     * @param  string $model
     * @return void
     */
    public static function makeModel(string $model): void
    {
        echo '[...] Generating model' . PHP_EOL;

        list($model_name, $model_class) = self::generateClass($model, 'model');

        $data = self::stubs()->readFile('Model.stub');
        $data = str_replace('CLASSNAME', $model_class, $data);
        $data = str_replace('TABLENAME', $model_name, $data);

        if (!Storage::path(config('storage.models'))->writeFile($model_class . '.php', $data)) {
            exit('[!] Failed to generate model ' . $model_class . PHP_EOL);
        }
        
        echo '[+] Model ' . $model_class . ' generated successfully' . PHP_EOL;
    }
 
    /**
     * generate migration file
     *
     * @param  string $migration
     * @return void
     */
    public static function makeMigration(string $migration): void
    {
        echo '[...] Generating migration' . PHP_EOL;

        list($migration_name, $migration_class) = self::generateClass($migration, 'migration');

        $data = self::stubs()->readFile('Migration.stub');
        $data = str_replace('CLASSNAME', $migration_class, $data);
        $data = str_replace('TABLENAME', $migration_name, $data);

        if (!Storage::path(config('storage.migrations'))->writeFile($migration_class . '.php', $data)) {
            exit('[!] Failed to generate migration ' . $migration_class . PHP_EOL);
        }
        
        echo '[+] Migration ' . $migration_class . ' generated successfully' . PHP_EOL;
    }

    /**
     * generate seed file
     *
     * @param  string $seed
     * @return void
     */
    public static function makeSeed(string $seed): void
    {
        echo '[...] Generating seed' . PHP_EOL;

        list($seed_name, $seed_class) = self::generateClass($seed, 'seed');

        $data = self::stubs()->readFile('Seed.stub');
        $data = str_replace('CLASSNAME', $seed_class, $data);
        $data = str_replace('TABLENAME', $seed_name . 's', $data);

        if (!Storage::path(config('storage.seeds'))->writeFile($seed_class . '.php', $data)) {
            exit('[!] Failed to generate seed ' . $seed_class . PHP_EOL);
        }
        
        echo '[+] Seed ' . $seed_class . ' generated successfully' . PHP_EOL;
    }
    
    /**
     * generate mail resources
     *
     * @param  string $mail
     * @return void
     */
    public static function makeMail(string $mail)
    {
        echo '[...] Generating mail' . PHP_EOL;

        list($mail_name, $mail_class) = self::generateClass($mail, 'mail');

        $data = self::stubs()->readFile('Mail.stub');
        $data = str_replace('CLASSNAME', $mail_class, $data);
        $data = str_replace('RESOURCENAME', $mail_name, $data);

        if (!Storage::path(config('storage.mails'))->writeFile($mail_class . '.php', $data)) {
            exit('[!] Failed to generate mail ' . $mail_class . PHP_EOL);
        }

        $data = self::stubs()->readFile('email.stub');

        if (!Storage::path(config('storage.views'))->add('emails')->writeFile($mail_name . '.php', $data)) {
            exit('[!] Failed to generate mail ' . $mail_class . PHP_EOL);
        }
        
        echo '[+] ' . $mail_class . ' generated successfully' . PHP_EOL;
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
            array_key_exists('controller', $options) &&
            !array_key_exists('namespace', $options) &&
            !array_key_exists('model', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('resource', $options) &&
            !array_key_exists('m', $options) &&
            !array_key_exists('mail', $options)
        ) {
            self::makeController($options['controller']);
        }
        
        else if (
            array_key_exists('controller', $options) &&
            array_key_exists('namespace', $options) &&
            !array_key_exists('model', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('resource', $options) &&
            !array_key_exists('m', $options) &&
            !array_key_exists('mail', $options)
        ) {
            self::makeController($options['controller'], $options['namespace']);
        }

        else if (
            array_key_exists('resource', $options) &&
            !array_key_exists('controller', $options) &&
            !array_key_exists('namespace', $options) &&
            !array_key_exists('model', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('m', $options) &&
            !array_key_exists('mail', $options)
        ) {
            self::makeController($options['resource'], 'admin');
            self::makeViews($options['resource']);
            self::makeModel($options['resource']);
            self::makeSeed($options['resource']);
            self::makeMigration($options['resource']);
            self::makeRoute($options['resource']);
        }

        else if (
            array_key_exists('model', $options) &&
            !array_key_exists('controller', $options) &&
            !array_key_exists('namespace', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('resource', $options) &&
            !array_key_exists('m', $options) &&
            !array_key_exists('mail', $options)
        ) {
            self::makeModel($options['model']);
        }

        else if (
            array_key_exists('migration', $options) &&
            !array_key_exists('controller', $options) &&
            !array_key_exists('namespace', $options) &&
            !array_key_exists('model', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('resource', $options) &&
            !array_key_exists('m', $options) &&
            !array_key_exists('mail', $options)
        ) {
            self::makeMigration($options['migration']);
        }

        else if (
            array_key_exists('migration', $options) &&
            array_key_exists('m', $options) &&
            !array_key_exists('controller', $options) &&
            !array_key_exists('namespace', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('resource', $options) &&
            !array_key_exists('model', $options) &&
            !array_key_exists('mail', $options)
        ) {
            self::makeMigration($options['migration']);
            self::makeModel($options['migration']);
        }

        else if (
            array_key_exists('seed', $options) &&
            !array_key_exists('controller', $options) &&
            !array_key_exists('namespace', $options) &&
            !array_key_exists('model', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('resource', $options) &&
            !array_key_exists('m', $options) &&
            !array_key_exists('mail', $options)
        ) {
            self::makeSeed($options['seed']);
        }

        else if (
            array_key_exists('request', $options) &&
            !array_key_exists('controller', $options) &&
            !array_key_exists('namespace', $options) &&
            !array_key_exists('model', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('resource', $options) &&
            !array_key_exists('m', $options) &&
            !array_key_exists('mail', $options)
        ) {
            echo '[...] Generating request validator' . PHP_EOL;

            $data = self::stubs()->readFile('Request.stub');
            $data = str_replace('CLASSNAME', $options['request'], $data);

            if (!Storage::path(config('storage.requests'))->writeFile($options['request'] . '.php', $data)) {
                exit('[!] Failed to generate request ' . $options['request'] . '.php' . PHP_EOL);
            }

            echo '[...] Request validator ' . $options['request'] . ' generated successfully' . PHP_EOL;
        }

        else if (
            array_key_exists('middleware', $options) &&
            !array_key_exists('controller', $options) &&
            !array_key_exists('namespace', $options) &&
            !array_key_exists('model', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('resource', $options) &&
            !array_key_exists('m', $options) &&
            !array_key_exists('mail', $options)
        ) {
            echo '[...] Generating middleware' . PHP_EOL;

            $data = self::stubs()->readFile('Middleware.stub');
            $data = str_replace('CLASSNAME', $options['middleware'], $data);

            if (!Storage::path(config('storage.middlewares'))->writeFile($options['middleware'] . '.php', $data)) {
                exit('[!] Failed to generate middleware ' . $options['middleware'] . '.php' . PHP_EOL);
            }

            echo '[...] Middleware ' . $options['middleware'] . ' generated successfully' . PHP_EOL;
        }

        else if (
            array_key_exists('mail', $options) &&
            !array_key_exists('controller', $options) &&
            !array_key_exists('namespace', $options) &&
            !array_key_exists('model', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('resource', $options) &&
            !array_key_exists('m', $options) &&
            !array_key_exists('middleware', $options)
        ) {
            self::makeMail($options['mail']);
        }
    }
}