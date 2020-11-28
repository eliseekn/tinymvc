<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
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

        return 'admin/resources/' . $resource;
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
     * handle cli
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
            !array_key_exists('table', $options) &&
            !array_key_exists('resource', $options)
        ) {
            echo '[+] Generating controller...' . PHP_EOL;

            $data = self::stubs()->readFile('Controller.stub');
            $data = str_replace('NAMESPACE', 'App\Controllers', $data);
            $data = str_replace('CLASSNAME', $options['controller'], $data);

            if (!Storage::path(config('storage.controllers'))->writeFile($options['controller'] . '.php', $data)) {
                exit('[!] Failed to generate controller ' . $options['controller'] . '.php' . PHP_EOL);
            } 
            
            echo '[+] Controller ' . $options['controller'] . '.php generated successfully';
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
            !array_key_exists('table', $options)
        ) {
            echo '[+] Generating resources...' . PHP_EOL;

            $resource = self::resourceName($options['resource']);
            $folder = self::resourceFolder($resource);

            if (!Storage::path(config('storage.views'))->isDir($folder)) {
                Storage::path(config('storage.views'))->createDir($folder);
            }

            foreach(self::stubs()->add('Resource')->getFiles() as $file) {
                $data = self::stubs()->add('Resource')->readFile($file);
                $data = str_replace('RESSOURCENAME', $resource, $data);
                $file = str_replace('stub', 'php', $file);

                if (!Storage::path(config('storage.views'))->add($folder)->writeFile($file, $data)) {
                    exit('[-] Failed to generate resourcess' . PHP_EOL);
                }
            }

            echo '[+] Resources for ' . $options['resource'] . ' generated successfully';
        }

        else if (
            array_key_exists('controller', $options) &&
            array_key_exists('resource', $options) &&
            !array_key_exists('namespace', $options) &&
            !array_key_exists('model', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('table', $options)
        ) {
            echo '[+] Generating controller and resources...' . PHP_EOL;

            $data = self::stubs()->readFile('Controller.stub');
            $data = str_replace('NAMESPACE', 'App\Controllers', $data);
            $data = str_replace('CLASSNAME', $options['controller'], $data);

            if (!Storage::path(config('storage.controllers'))->writeFile($options['controller'] . '.php', $data)) {
                exit('[!] Failed to generate controller ' . $options['controller'] . '.php' . PHP_EOL);
            } 
            
            echo '[+] Controller ' . $options['controller'] . '.php generated successfully';

            //generate resourcess
            $resource = self::resourceName($options['resource']);
            $folder = self::resourceFolder($resource);

            if (!Storage::path(config('storage.views'))->isDir($folder)) {
                Storage::path(config('storage.views'))->createDir($folder);
            }

            foreach(self::stubs()->add('Resource')->getFiles() as $file) {
                $data = self::stubs()->add('Resource')->readFile($file);
                $data = str_replace('RESSOURCENAME', $resource, $data);
                $file = str_replace('stub', 'php', $file);

                if (!Storage::path(config('storage.views'))->add($folder)->writeFile($file, $data)) {
                    exit('[-] Failed to generate resources' . PHP_EOL);
                }
            }

            echo '[+] Resources for ' . $options['resource'] . ' generated successfully';
        }

        else if (
            array_key_exists('controller', $options) &&
            array_key_exists('namespace', $options) &&
            !array_key_exists('model', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('table', $options) &&
            !array_key_exists('resource', $options)
        ) {
            echo '[+] Generating controller...' . PHP_EOL;

            $data = self::stubs()->readFile('Controller.stub');
            $data = str_replace('NAMESPACE', 'App\Controllers\\' . $options['namespace'], $data);
            $data = str_replace('CLASSNAME', $options['controller'], $data);

            $path = Storage::path(config('storage.controllers'));

            if (!$path->isDir($options['namespace'])) {
                $path->createDir($options['namespace']);
            }

            if (!$path->add($options['namespace'])->writeFile($options['controller'] . '.php', $data)) {
                exit('[!] Failed to generate controller ' . $options['controller'] . '.php' . PHP_EOL);
            }
            
            echo '[+] Controller ' . $options['controller'] . '.php generated successfully';
        }

        else if (
            array_key_exists('controller', $options) &&
            array_key_exists('namespace', $options) &&
            array_key_exists('resource', $options) &&
            !array_key_exists('model', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('table', $options)
        ) {
            echo '[+] Generating controller and resources...' . PHP_EOL;

            $data = self::stubs()->readFile('Controller.stub');
            $data = str_replace('NAMESPACE', 'App\Controllers\\' . $options['namespace'], $data);
            $data = str_replace('CLASSNAME', $options['controller'], $data);

            $path = Storage::path(config('storage.controllers'));

            if (!$path->isDir($options['namespace'])) {
                $path->createDir($options['namespace']);
            }

            if (!$path->add($options['namespace'])->writeFile($options['controller'] . '.php', $data)) {
                exit('[!] Failed to generate controller ' . $options['controller'] . '.php' . PHP_EOL);
            }

            echo '[+] Controller ' . $options['controller'] . '.php generated successfully';

            //create resourcess
            $resource = self::resourceName($options['resource']);
            $folder = self::resourceFolder($resource);
            
            if (!Storage::path(config('storage.views'))->isDir($folder)) {
                Storage::path(config('storage.views'))->createDir($folder);
            }

            foreach(self::stubs()->add('Resource')->getFiles() as $file) {
                $data = self::stubs()->add('Resource')->readFile($file);
                $data = str_replace('RESSOURCENAME', $resource, $data);
                $file = str_replace('stub', 'php', $file);

                if (!Storage::path(config('storage.views'))->add($folder)->writeFile($file, $data)) {
                    exit('[-] Failed to generate resources' . PHP_EOL);
                }
            }

            echo '[+] Resources for ' . $options['resource'] . ' generated successfully';
        }

        else if (
            array_key_exists('model', $options) &&
            array_key_exists('table', $options) &&
            !array_key_exists('controller', $options) &&
            !array_key_exists('namespace', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('resource', $options)
        ) {
            echo '[+] Generating model for table ' . $options['table'] . '...' . PHP_EOL;

            $data = self::stubs()->readFile('Model.stub');
            $data = str_replace('NAMESPACE', 'App\Database\Models', $data);
            $data = str_replace('CLASSNAME', $options['model'], $data);
            $data = str_replace('TABLENAME', strtolower($options['table']), $data);

            if (!Storage::path(config('storage.models'))->writeFile($options['model'] . '.php', $data)) {
                exit('[!] Failed to generate model ' . $options['model'] . '.php' . PHP_EOL);
            }

            echo '[+] Model ' . $options['model'] . '.php generated successfully';
        }

        else if (
            array_key_exists('model', $options) &&
            array_key_exists('table', $options) &&
            array_key_exists('namespace', $options) &&
            !array_key_exists('controller', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('resource', $options)
        ) {
            echo '[+] Generating model for table ' . $options['table'] . '...' . PHP_EOL;

            $data = self::stubs()->readFile('Model.stub');
            $data = str_replace('NAMESPACE', 'App\Database\Models\\' . $options['namespace'], $data);
            $data = str_replace('CLASSNAME', $options['model'], $data);
            $data = str_replace('TABLENAME', strtolower($options['table']), $data);

            $path = Storage::path(config('storage.models'));

            if (!$path->isDir($options['namespace'])) {
                $path->createDir($options['namespace']);
            }

            if (!$path->add($options['namespace'])->writeFile($options['model'] . '.php', $data)) {
                exit('[!] Failed to generate model ' . $options['model'] . '.php' . PHP_EOL);
            }

            echo '[+] Model ' . $options['model'] . '.php generated successfully';
        }

        else if (
            array_key_exists('migration', $options) &&
            array_key_exists('table', $options) &&
            !array_key_exists('controller', $options) &&
            !array_key_exists('namespace', $options) &&
            !array_key_exists('model', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('resource', $options)
        ) {
            echo '[+] Generating migration for table ' . $options['table'] . '...' . PHP_EOL;

            $data = self::stubs()->readFile('Migration.stub');
            $data = str_replace('NAMESPACE', 'App\Database\Migrations', $data);
            $data = str_replace('CLASSNAME', $options['migration'], $data);
            $data = str_replace('TABLENAME', strtolower($options['table']), $data);

            if (!Storage::path(config('storage.migrations'))->writeFile($options['migration'] . '.php', $data)) {
                exit('[!] Failed to generate migration ' . $options['migration'] . '.php' . PHP_EOL);
            }

            echo '[+] Migration ' . $options['migration'] . '.php generated successfully';
        }

        else if (
            array_key_exists('migration', $options) &&
            array_key_exists('model', $options) &&
            array_key_exists('table', $options) &&
            !array_key_exists('controller', $options) &&
            !array_key_exists('namespace', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('resource', $options)
        ) {
            echo '[+] Generating migration for table ' . $options['table'] . 'with model ' . $options['model'] . '...' . PHP_EOL;

            $data = self::stubs()->readFile('Migration.stub');
            $data = str_replace('NAMESPACE', 'App\Database\Migrations', $data);
            $data = str_replace('CLASSNAME', $options['migration'], $data);
            $data = str_replace('TABLENAME', strtolower($options['table']), $data);

            if (!Storage::path(config('storage.migrations'))->writeFile($options['migration'] . '.php', $data)) {
                exit('[!] Failed to generate migration ' . $options['migration'] . '.php' . PHP_EOL);
            }

            echo '[+] Migration ' . $options['migration'] . '.php generated successfully';

            //generate model
            $data = self::stubs()->readFile('Model.stub');
            $data = str_replace('NAMESPACE', 'App\Database\Models', $data);
            $data = str_replace('CLASSNAME', $options['model'], $data);
            $data = str_replace('TABLENAME', strtolower($options['table']), $data);

            if (!Storage::path(config('storage.models'))->writeFile($options['model'] . '.php', $data)) {
                exit('[!] Failed to generate model ' . $options['model'] . '.php' . PHP_EOL);
            }

            echo '[+] Model ' . $options['model'] . '.php generated successfully';
        }

        else if (
            array_key_exists('migration', $options) &&
            array_key_exists('table', $options) &&
            array_key_exists('namespace', $options) &&
            !array_key_exists('controller', $options) &&
            !array_key_exists('model', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('resource', $options)
        ) {
            echo '[+] Generating migration for table ' . $options['table'] . '...' . PHP_EOL;

            $data = self::stubs()->readFile('Migration.stub');
            $data = str_replace('NAMESPACE', 'App\Database\Migration\\' . $options['namespace'], $data);
            $data = str_replace('CLASSNAME', $options['migration'], $data);
            $data = str_replace('TABLENAME', strtolower($options['table']), $data);

            $path = Storage::path(config('storage.migrations'));

            if (!$path->isDir($options['namespace'])) {
                $path->createDir($options['namespace']);
            }

            if (!$path->add($options['namespace'])->writeFile($options['migration'] . '.php', $data)) {
                exit('[!] Failed to generate migration ' . $options['migration'] . '.php' . PHP_EOL);
            }

            echo '[+] Migration ' . $options['migration'] . '.php generated successfully';
        }

        else if (
            array_key_exists('migration', $options) &&
            array_key_exists('model', $options) &&
            array_key_exists('namespace', $options) &&
            array_key_exists('table', $options) &&
            !array_key_exists('controller', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('resource', $options)
        ) {
            echo '[+] Generating migration for table ' . $options['table'] . 'with model ' . $options['model'] . '...' . PHP_EOL;

            $data = self::stubs()->readFile('Migration.stub');
            $data = str_replace('NAMESPACE', 'App\Database\Migration\\' . $options['namespace'], $data);
            $data = str_replace('CLASSNAME', $options['migration'], $data);
            $data = str_replace('TABLENAME', strtolower($options['table']), $data);

            $path = Storage::path(config('storage.migrations'));

            if (!$path->isDir($options['namespace'])) {
                $path->createDir($options['namespace']);
            }

            if (!$path->add($options['namespace'])->writeFile($options['migration'] . '.php', $data)) {
                exit('[!] Failed to generate migration ' . $options['migration'] . '.php' . PHP_EOL);
            }

            echo '[+] Migration ' . $options['migration'] . '.php generated successfully';

            //
            $data = self::stubs()->readFile('Model.stub');
            $data = str_replace('NAMESPACE', 'App\Database\Models\\' . $options['namespace'], $data);
            $data = str_replace('CLASSNAME', $options['model'], $data);
            $data = str_replace('TABLENAME', strtolower($options['table']), $data);

            $path = Storage::path(config('storage.models'));

            if (!$path->isDir($options['namespace'])) {
                $path->createDir($options['namespace']);
            }

            if (!$path->add($options['namespace'])->writeFile($options['model'] . '.php', $data)) {
                exit('[!] Failed to generate model ' . $options['model'] . '.php' . PHP_EOL);
            }

            echo '[+] Model ' . $options['model'] . '.php generated successfully';
        }

        else if (
            array_key_exists('seed', $options) &&
            array_key_exists('table', $options) &&
            !array_key_exists('controller', $options) &&
            !array_key_exists('namespace', $options) &&
            !array_key_exists('model', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('resource', $options)
        ) {
            echo '[+] Generating seed...' . PHP_EOL;

            $data = self::stubs()->readFile('Seed.stub');
            $data = str_replace('NAMESPACE', 'App\Database\Seeds', $data);
            $data = str_replace('CLASSNAME', $options['seed'], $data);
            $data = str_replace('TABLENAME', strtolower($options['table']), $data);

            if (!Storage::path(config('storage.seeds'))->writeFile($options['seed'] . '.php', $data)) {
                exit('[!] Failed to generate seed ' . $options['seed'] . '.php' . PHP_EOL);
            }

            echo '[+] Seed ' . $options['seed'] . '.php generated successfully';
        }

        else if (
            array_key_exists('seed', $options) &&
            array_key_exists('table', $options) &&
            array_key_exists('namespace', $options) &&
            !array_key_exists('controller', $options) &&
            !array_key_exists('model', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('resource', $options)
        ) {
            echo '[+] Generating migration for table ' . $options['table'] . '...' . PHP_EOL;

            $data = self::stubs()->readFile('Seed.stub');
            $data = str_replace('NAMESPACE', 'App\Database\Seeds\\' . $options['namespace'], $data);
            $data = str_replace('CLASSNAME', $options['seed'], $data);
            $data = str_replace('TABLENAME', strtolower($options['table']), $data);

            $path = Storage::path(config('storage.seeds'));

            if (!$path->isDir($options['namespace'])) {
                $path->createDir($options['namespace']);
            }

            if (!$path->add($options['namespace'])->writeFile($options['seed'] . '.php', $data)) {
                exit('[!] Failed to generate seed ' . $options['seed'] . '.php' . PHP_EOL);
            }

            echo '[+] Seed ' . $options['seed'] . '.php generated successfully';
        }

        else if (
            array_key_exists('request', $options) &&
            !array_key_exists('controller', $options) &&
            !array_key_exists('namespace', $options) &&
            !array_key_exists('model', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('middleware', $options) &&
            !array_key_exists('table', $options) &&
            !array_key_exists('resource', $options)
        ) {
            echo '[+] Generating request validator...' . PHP_EOL;

            $data = self::stubs()->readFile('Request.stub');
            $data = str_replace('NAMESPACE', 'App\Requests', $data);
            $data = str_replace('CLASSNAME', $options['request'], $data);

            if (!Storage::path(config('storage.requests'))->writeFile($options['request'] . '.php', $data)) {
                exit('[!] Failed to generate request ' . $options['request'] . '.php' . PHP_EOL);
            }

            echo '[+] Request validator ' . $options['request'] . '.php generated successfully';
        }

        else if (
            array_key_exists('middleware', $options) &&
            !array_key_exists('controller', $options) &&
            !array_key_exists('namespace', $options) &&
            !array_key_exists('model', $options) &&
            !array_key_exists('migration', $options) &&
            !array_key_exists('seed', $options) &&
            !array_key_exists('request', $options) &&
            !array_key_exists('table', $options) &&
            !array_key_exists('resource', $options)
        ) {
            echo '[+] Generating middleware...' . PHP_EOL;

            $data = self::stubs()->readFile('Middleware.stub');
            $data = str_replace('NAMESPACE', 'App\Middlewares', $data);
            $data = str_replace('CLASSNAME', $options['middleware'], $data);

            if (!Storage::path(config('storage.middlewares'))->writeFile($options['middleware'] . '.php', $data)) {
                exit('[!] Failed to generate middleware ' . $options['middleware'] . '.php' . PHP_EOL);
            }

            echo '[+] Middleware ' . $options['middleware'] . '.php generated successfully';
        }
        
        exit('[+] All operations done' . PHP_EOL);
    }
}