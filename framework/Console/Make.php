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
     * @param  mixed $message
     * @param  mixed $exit
     * @return mixed
     */
    private static function log(string $message, bool $exit = true)
    {
        return $exit ? exit($message . PHP_EOL) : print($message . PHP_EOL);
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
    public static function makeRoute(string $controller): void
    {
        list($name, $class) = self::generateClass($controller, 'controller');

        $data = self::stubs()->readFile('Route.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('RESOURCENAME', $name, $data);

        if (!Storage::path(config('storage.routes'))->writeFile('admin.php', $data, true)) {
            self::log('[!] Failed to generate routes for "' . $class . '"');
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
    public static function makeController(string $controller, ?string $namespace = null): void
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
            self::log('[!] Failed to generate controller "' . $class . '"');
        }
        
        self::log('[+] Controller "' . $class . '" generated successfully', false);
    }

    /**
     * generate model file
     *
     * @param  string $model
     * @return void
     */
    public static function makeModel(string $model): void
    {
        list($name, $class) = self::generateClass($model, '');

        $data = self::stubs()->readFile('Model.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('TABLENAME', $name, $data);

        if (!Storage::path(config('storage.models'))->writeFile($class . '.php', $data)) {
            self::log('[!] Failed to generate model "' . $class . '"');
        }
        
        self::log('[+] Model "' . $class . '" generated successfully', false);
    }
 
    /**
     * generate migration file
     *
     * @param  string $migration
     * @return void
     */
    public static function makeMigration(string $migration): void
    {
        list($name, $class) = self::generateClass($migration, 'migration');

        $data = self::stubs()->readFile('Migration.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('TABLENAME', $name, $data);

        if (!Storage::path(config('storage.migrations'))->writeFile($class . '.php', $data)) {
            self::log('[!] Failed to generate migration "' . $class . '"');
        }
        
        self::log('[+] Migration "' . $class . '" generated successfully', false);
    }

    /**
     * generate seed file
     *
     * @param  string $seed
     * @return void
     */
    public static function makeSeed(string $seed): void
    {
        list($name, $class) = self::generateClass($seed, 'seed');

        $data = self::stubs()->readFile('Seed.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('TABLENAME', $name, $data);

        if (!Storage::path(config('storage.seeds'))->writeFile($class . '.php', $data)) {
            self::log('[!] Failed to generate seed "' . $class . '"');
        }
        
        self::log('[+] Seed ""' . $class . '"" generated successfully', false);
    }
    
    /**
     * generate request validator file
     *
     * @param  string $request
     * @return void
     */
    public static function makeRequest(string $request): void
    {
        $data = self::stubs()->readFile('Request.stub');
        $data = str_replace('CLASSNAME', $request, $data);

        if (!Storage::path(config('storage.requests'))->writeFile($request . '.php', $data)) {
            self::log('[!] Failed to generate request "' . $request . '"');
        }

        self::log('[+] Request validator "' . $request . '" generated successfully', false);
    }
    
    /**
     * generate middleware file
     *
     * @param  string $middleware
     * @return void
     */
    public static function makeMiddleware(string $middleware): void
    {
        $data = self::stubs()->readFile('Middleware.stub');
        $data = str_replace('CLASSNAME', $middleware, $data);

        if (!Storage::path(config('storage.middlewares'))->writeFile($middleware . '.php', $data)) {
            self::log('[!] Failed to generate middleware "' . $middleware . '"');
        }

        self::log('[+] Middleware "' . $middleware . '" generated successfully', false);
    }
    
    /**
     * generate views file
     *
     * @param  string $resource
     * @return void
     */
    public static function makeViews(string $resource): void
    {
        list($name, $resource_folder) = self::generateResource($resource);

        foreach(self::stubs()->add('views' . DIRECTORY_SEPARATOR . 'admin')->getFiles() as $file) {
            $data = self::stubs()->add('views' . DIRECTORY_SEPARATOR . 'admin')->readFile($file);
            $data = str_replace('RESOURCENAME', $name, $data);
            $file = str_replace('stub', 'html.twig', $file);

            if (!Storage::path(config('storage.views'))->add($resource_folder)->writeFile($file, $data)) {
                self::log('[-] Failed to generate views for "' . $resource . '"');
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
    public static function makeMail(string $mail): void
    {
        list($name, $class) = self::generateClass($mail, 'mail');

        $data = self::stubs()->readFile('Mail.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('RESOURCENAME', $name, $data);

        if (!Storage::path(config('storage.mails'))->writeFile($class . '.php', $data)) {
            self::log('[!] Failed to generate mail "' . $class . '"');
        }

        $data = self::stubs()->add('views')->readFile('email.stub');

        if (!Storage::path(config('storage.views'))->add('emails')->writeFile($name . '.html.twig', $data)) {
            self::log('[!] Failed to generate view template "' . $name . '"');
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
    public static function makeView(?string $view = null, ?string $layout = null): void
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
            self::log('[!] Failed to generate view template "' . $view . '"');
        }
        
        self::log('[+] View template "' . $view . '" generated successfully', false);
    }
}