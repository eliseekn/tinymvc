<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Console;

use Framework\System\Storage;

/**
 * Manage application stubs
 */
class Make
{
    /**
     * print console message
     *
     * @param  string $message
     * @param  bool $exit
     * @param  string $type
     * @return mixed
     */
    private static function log(string $message, bool $exit, string $type = 'success')
    {
        if ($type === 'error') {
            echo "\e[1;37;41m{$message}\e[0m";
        } else {
            echo "\e[0;32;40m{$message}\e[0m";
        }

        echo PHP_EOL;

        if ($exit) {
            exit();
        }
    }

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
        return 'admin' . DIRECTORY_SEPARATOR . $resource;
    }
    
    /**
     * generate resource name
     *
     * @param  string $resource
     * @return string
     */
    private static function resourceName(string $resource): string
    {
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

        if ($name[-1] !== 's') {
            $name .= 's';
        }

        if ($class === 'migration') {
            $class = 'table';
        }

        $class = ucfirst($name) . ucfirst($class);

        if (strpos(strtolower($class), 'table')) {
            $class .= date('_YmdHis');
        }

        return [$name, $class];
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
    public static function createRoute(string $controller): void
    {
        list($name, $class) = self::generateClass($controller, 'controller');

        $data = self::stubs()->readFile('Route.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('RESOURCENAME', $name, $data);

        if (!Storage::path(config('storage.routes'))->writeFile('admin.php', $data, true)) {
            self::log('[!] Failed to generate routes for "' . $class . '"', true, 'error');
        }
        
        self::log('[+] Routes for "' . $class . '" generated successfully', false);
    }
    
    /**
     * generate controller file
     *
     * @param  string $controller
     * @param  string|null $namespace
     * @return void
     */
    public static function createController(string $controller, ?string $namespace = null): void
    {
        list($name, $class) = self::generateClass($controller, 'controller');

        $data = self::stubs()->readFile('Controller.stub');
        
        $data = is_null($namespace) 
            ? str_replace('NAMESPACE', 'App\Controllers', $data) 
            : str_replace('NAMESPACE', 'App\Controllers\\' . ucfirst($namespace), $data);
        
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('RESOURCENAME', $name, $data);
        $data = str_replace('RESOURCEMODEL', ucfirst($name) . 'Model', $data);

        $path = Storage::path(config('storage.controllers'));

        if (!is_null($namespace)) {
            $path->add(ucfirst($namespace));
        }

        if (!$path->writeFile($class . '.php', $data)) {
            self::log('[!] Failed to generate controller "' . $class . '"', true, 'error');
        }
        
        self::log('[+] Controller "' . $class . '" generated successfully', false);
    }

    /**
     * generate repository file
     *
     * @param  string $repository
     * @return void
     */
    public static function createRepository(string $repository): void
    {
        list($name, $class) = self::generateClass($repository, '');

        $data = self::stubs()->readFile('Repository.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('TABLENAME', $name, $data);

        if (!Storage::path(config('storage.repositories'))->writeFile($class . '.php', $data)) {
            self::log('[!] Failed to generate repository "' . $class . '"', true, 'error');
        }
        
        self::log('[+] Repository "' . $class . '" generated successfully', false);
    }
 
    /**
     * generate migration file
     *
     * @param  string $migration
     * @return void
     */
    public static function createMigration(string $migration): void
    {
        list($name, $class) = self::generateClass($migration, 'migration');

        $data = self::stubs()->readFile('Migration.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('TABLENAME', $name, $data);

        if (!Storage::path(config('storage.migrations'))->writeFile($class . '.php', $data)) {
            self::log('[!] Failed to generate migration "' . $class . '"', true, 'error');
        }
        
        self::log('[+] Migration table "' . $class . '" generated successfully', false);
    }

    /**
     * generate seed file
     *
     * @param  string $seed
     * @return void
     */
    public static function createSeed(string $seed): void
    {
        list($name, $class) = self::generateClass($seed, 'seed');

        $data = self::stubs()->readFile('Seed.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('TABLENAME', $name, $data);

        if (!Storage::path(config('storage.seeds'))->writeFile($class . '.php', $data)) {
            self::log('[!] Failed to generate seed "' . $class . '"', true, 'error');
        }
        
        self::log('[+] Seed ""' . $class . '"" generated successfully', false);
    }
    
    /**
     * generate request validator file
     *
     * @param  string $request
     * @return void
     */
    public static function createRequest(string $request): void
    {
        list($name, $class) = self::generateClass($request, 'request');

        $data = self::stubs()->readFile('Request.stub');
        $data = str_replace('CLASSNAME', $class, $data);

        if (!Storage::path(config('storage.requests'))->writeFile($class . '.php', $data)) {
            self::log('[!] Failed to generate request "' . $class . '"', true, 'error');
        }

        self::log('[+] Request validator "' . $class . '" generated successfully', false);
    }
    
    /**
     * generate middleware file
     *
     * @param  string $middleware
     * @return void
     */
    public static function createMiddleware(string $middleware): void
    {
        list($name, $class) = self::generateClass($middleware, 'middleware');
        
        $data = self::stubs()->readFile('Middleware.stub');
        $data = str_replace('CLASSNAME', $class, $data);

        if (!Storage::path(config('storage.middlewares'))->writeFile($class . '.php', $data)) {
            self::log('[!] Failed to generate middleware "' . $class . '"', true, 'error');
        }

        self::log('[+] Middleware "' . $class . '" generated successfully', false);
    }
    
    /**
     * generate views file
     *
     * @param  string $resource
     * @return void
     */
    public static function createViews(string $resource): void
    {
        list($name, $resource_folder) = self::generateResource($resource);

        foreach(self::stubs()->add('views' . DIRECTORY_SEPARATOR . 'admin')->getFiles() as $file) {
            $data = self::stubs()->add('views' . DIRECTORY_SEPARATOR . 'admin')->readFile($file);
            $data = str_replace('RESOURCENAME', $name, $data);
            $file = str_replace('stub', 'html.twig', $file);

            if (!Storage::path(config('storage.views'))->add($resource_folder)->writeFile($file, $data)) {
                self::log('[-] Failed to generate views for "' . $resource . '"', true, 'error');
            }
        }

        self::log('[+] Views for "' . $resource . '" generated successfully', false);
    }
    
    /**
     * generate mail resources
     *
     * @param  string $mail
     * @return void
     */
    public static function createMail(string $mail): void
    {
        list($name, $class) = self::generateClass($mail, 'mail');

        $data = self::stubs()->readFile('Mail.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('RESOURCENAME', $name, $data);

        if (!Storage::path(config('storage.mails'))->writeFile($class . '.php', $data)) {
            self::log('[!] Failed to generate mail "' . $class . '"', true, 'error');
        }

        $data = self::stubs()->add('views')->readFile('email.stub');

        if (!Storage::path(config('storage.views'))->add('emails')->writeFile($name . '.html.twig', $data)) {
            self::log('[!] Failed to generate view template "' . $name . '"', true, 'error');
        }
        
        self::log('[+] Mail "' . $class . '" generated successfully', false);
    }
    
    /**
     * generate view template
     *
     * @param  string|null $view
     * @param  string|null $layout
     * @return void
     */
    public static function createView(?string $view = null, ?string $layout = null): void
    {
        $data = is_null($view) && !is_null($layout)
            ? self::stubs()->add('views')->readFile('layout.stub')
            : self::stubs()->add('views')->readFile('blank.stub');

        $data = is_null($layout) 
            ? str_replace('LAYOUTNAME', '', $data)
            : str_replace('LAYOUTNAME', '{% extends "layouts/' . $layout . '.html.twig" %}', $data);

        $data = is_null($view) ? $data : str_replace('RESOURCENAME', $view, $data);
        
        $path = Storage::path(config('storage.views'));

        if (is_null($view) && !is_null($layout)) {
            $path->add('layouts');
        } else {
            $path->add('admin');
        }
        
        $view = is_null($view) && !is_null($layout) ? $layout : $view;

        if (!$path->writeFile($view . '.html.twig', $data)) {
            self::log('[!] Failed to generate view template "' . $view . '"', true, 'error');
        }
        
        self::log('[+] View template "' . $view . '" generated successfully', false);
    }
}