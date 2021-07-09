<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\Make;

use Core\Support\Storage;

/**
 * Create templates from stubs
 */
class Make
{
    /**
     * Get stubs storage path
     */
    private static function stubs(): Storage
    {
        return Storage::path(config('storage.stubs'));
    }

    public static function fixPluralTypo(string $word, bool $remove = false)
    {
        if (!$remove) {
            if ($word[-1] === 'y') {
                $word = rtrim($word, 'y');
                $word .= 'ies';
            }
        
            if ($word[-1] !== 's') {
                $word .= 's';
            }
        } else {
            if ($word[-3] === 'ies') {
                $word = rtrim($word, 'ies');
                $word .= 'y';
            }
        
            if ($word[-1] === 's') {
                $word = rtrim($word, 's');
            }
        }

        return $word;
    }
    
    public static function generateClass(string $base_name, string $suffix = '', bool $singular = false, bool $force_singlular = false)
    {
        $name = ucfirst(strtolower($base_name));

        if (!$singular) {
            $name = self::fixPluralTypo($name);
        }

        if ($force_singlular) {
            $name = self::fixPluralTypo($name, true);
        }

        if (strpos($name, '_')) {
            list($f, $s) = explode('_', $name);
            $name = ucfirst($f) . ucfirst($s);
        }

        if ($suffix === 'migration') {
            $suffix = 'table';
        }

        $class = $name . ucfirst($suffix);

        if (strpos($class, 'Table')) {
            $class .= date('_YmdHis');
        }

        return [lcfirst($name), $class];
    }
    
    public static function createController(string $controller, ?string $namespace = null)
    {
        list($name, $class) = self::generateClass($controller, 'controller');

        $data = self::stubs()->readFile('Controller.stub');
        
        $data = is_null($namespace) 
            ? str_replace('NAMESPACE', 'App\Http\Controllers', $data) 
            : str_replace('NAMESPACE', 'App\Http\Controllers\\' . ucfirst($namespace), $data);
        
        $data = str_replace('CLASSNAME', $class, $data);

        $path = Storage::path(config('storage.controllers'));

        if (!is_null($namespace)) {
            $path->addPath(ucfirst($namespace));
        }

        if (!$path->writeFile($class . '.php', $data)) {
            return false;
        }
        
        return true;
    }

    public static function createRepository(string $repository)
    {
        list($name, $class) = self::generateClass($repository, 'repository', true, true);

        $data = self::stubs()->readFile('Repository.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('MODELNAME', ucfirst($name), $data);

        if (!Storage::path(config('storage.repositories'))->writeFile($class . '.php', $data)) {
            return false;
        }
        
        return true;
    }
 
    public static function createModel(string $model)
    {
        list($name, $class) = self::generateClass($model, '');

        $data = self::stubs()->readFile('Model.stub');
        $data = str_replace('CLASSNAME', self::fixPluralTypo($class, true), $data);
        $data = str_replace('TABLENAME', $name, $data);

        if (!Storage::path(config('storage.models'))->writeFile(self::fixPluralTypo($class, true) . '.php', $data)) {
            return false;
        }
        
        return true;
    }
 
    public static function createMigration(string $migration)
    {
        list($name, $class) = self::generateClass($migration, 'migration');

        $data = self::stubs()->readFile('Migration.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('TABLENAME', $name, $data);

        if (!Storage::path(config('storage.migrations'))->writeFile($class . '.php', $data)) {
            return false;
        }
        
        return true;
    }

    public static function createSeed(string $seed)
    {
        list($name, $class) = self::generateClass($seed, 'seed', true, true);

        $data = self::stubs()->readFile('Seed.stub');
        $data = str_replace('CLASSNAME', self::fixPluralTypo($class, true), $data);
        $data = str_replace('TABLENAME', self::fixPluralTypo($name), $data);

        if (!Storage::path(config('storage.seeds'))->writeFile(self::fixPluralTypo($class, true) . '.php', $data)) {
            return false;
        }
        
        return true;
    }
    
    public static function createHelper(string $helper)
    {
        list($name, $class) = self::generateClass($helper, 'helper', true);

        $data = self::stubs()->readFile('Helper.stub');
        $data = str_replace('CLASSNAME', $class, $data);

        if (!Storage::path(config('storage.helpers'))->writeFile($class . '.php', $data)) {
            return false;
        }
        
        return true;
    }
    
    public static function createTest(string $test)
    {
        list($name, $class) = self::generateClass($test, 'test', true);

        $data = self::stubs()->readFile('Test.stub');
        $data = str_replace('CLASSNAME', $class, $data);

        if (!Storage::path(config('storage.tests'))->writeFile($class . '.php', $data)) {
            return false;
        }
        
        return true;
    }
    
    public static function createValidator(string $validator)
    {
        list($name, $class) = self::generateClass($validator, '', true);

        $data = self::stubs()->readFile('Validator.stub');
        $data = str_replace('CLASSNAME', $class, $data);

        if (!Storage::path(config('storage.validators'))->writeFile($class . '.php', $data)) {
            return false;
        }

        return true;
    }
    
    public static function createMiddleware(string $middleware)
    {
        list($name, $class) = self::generateClass($middleware, '', true);
        
        $data = self::stubs()->readFile('Middleware.stub');
        $data = str_replace('CLASSNAME', $class, $data);

        if (!Storage::path(config('storage.middlewares'))->writeFile($class . '.php', $data)) {
            return false;
        }

        return true;
    }
    
    public static function createMail(string $mail)
    {
        list($name, $class) = self::generateClass($mail, 'mail');

        $data = self::stubs()->readFile('Mail.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('RESOURCENAME', $name, $data);

        if (!Storage::path(config('storage.mails'))->writeFile($class . '.php', $data)) {
            return false;
        }

        $data = self::stubs()->addPath('views')->readFile('email.stub');

        if (!Storage::path(config('storage.views'))->addPath('emails')->writeFile($name . '.html.twig', $data)) {
            return false;
        }
        
        return true;
    }
    
    public static function createView(?string $view, ?string $layout)
    {
        $data = is_null($view) && !is_null($layout)
            ? self::stubs()->addPath('views')->readFile('layout.stub')
            : self::stubs()->addPath('views')->readFile('blank.stub');

        $data = str_replace('LAYOUTNAME', '{% extends "layouts/' . $layout . '.html.twig" %}', $data);
        $data = is_null($view) ? $data : str_replace('RESOURCENAME', $view, $data);
        
        $path = Storage::path(config('storage.views'));

        if (is_null($view) && !is_null($layout)) {
            $path->addPath('layouts');
        }
        
        $view = is_null($view) && !is_null($layout) ? $layout : $view;

        if (!$path->writeFile($view . '.html.twig', $data)) {
            return false;
        }
        
        return true;
    }
    
    public static function createCommand(string $cmd, string $name, string $description)
    {
        list($_name, $class) = self::generateClass($cmd, '', true);

        $data = self::stubs()->readFile('Command.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('COMMANDNAME', $name, $data);
        $data = str_replace('COMMANDDESCPTION', $description, $data);

        if (!Storage::path(config('storage.commands'))->writeFile($class . '.php', $data)) {
            return false;
        }

        return true;
    }
}
