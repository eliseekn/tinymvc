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

    private static function removeUnderscore(string $word)
    {
        if (strpos($word, '_')) {
            $words = explode('_', $word);
            $word = '';

            foreach ($words as $w) {
                $word .= ucfirst($w);
            }
        }

        return $word;
    }

    private static function addNamespace(string $data, string $base, ?string $namespace = null)
    {
        return is_null($namespace) ? str_replace('NAMESPACE', $base, $data) 
            : str_replace('NAMESPACE', "{$base}\\{$namespace}", $data);
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

        if (!$singular) $name = self::fixPluralTypo($name);
        if ($force_singlular) $name = self::fixPluralTypo($name, true);

        $name = self::removeUnderscore($name);

        if ($suffix === 'migration') $suffix = 'table';

        $suffix = self::removeUnderscore($suffix);

        $class = $name . ucfirst($suffix);

        if (strpos($class, 'Table')) $class .= date('_YmdHis');

        return [lcfirst($name), $class];
    }
    
    public static function createController(string $controller, ?string $namespace = null)
    {
        list(, $class) = self::generateClass($controller, 'controller', true, true);

        $data = self::stubs()->readFile('Controller.stub');
        $data = self::addNamespace($data, 'App\Http\Controllers', $namespace);
        $data = str_replace('CLASSNAME', $class, $data);

        $storage = Storage::path(config('storage.controllers'));

        if (!is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        if (!$storage->writeFile($class . '.php', $data)) {
            return false;
        }
        
        return true;
    }

    public static function createModel(string $model, ?string $namespace = null)
    {
        list($name, $class) = self::generateClass($model, '');

        $data = self::stubs()->readFile('Model.stub');
        $data = self::addNamespace($data, 'App\Database\Models', $namespace);
        $data = str_replace('CLASSNAME', self::fixPluralTypo($class, true), $data);
        $data = str_replace('TABLENAME', $name, $data);

        $storage = Storage::path(config('storage.models'));

        if (!is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        if (!$storage->writeFile(self::fixPluralTypo($class, true) . '.php', $data)) {
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
    
    public static function createFactory(string $factory, ?string $namespace = null)
    {
        list($name, $class) = self::generateClass($factory, 'factory', true, true);

        $data = self::stubs()->readFile('Factory.stub');
        $data = self::addNamespace($data, 'App\Database\Factories', $namespace);
        $data = str_replace('CLASSNAME', self::fixPluralTypo($class, true), $data);
        $data = str_replace('MODELNAME', self::fixPluralTypo(ucfirst($name), true), $data);

        $storage = Storage::path(config('storage.factories'));

        if (!is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        if (!$storage->writeFile(self::fixPluralTypo($class, true) . '.php', $data)) {
            return false;
        }
        
        return true;
    }
    
    public static function createRepository(string $repository, ?string $namespace = null)
    {
        list($name, $class) = self::generateClass($repository, 'repository', true, true);

        $data = self::stubs()->readFile('Repository.stub');
        $data = self::addNamespace($data, 'App\Database\Repositories', $namespace);
        $data = str_replace('CLASSNAME', self::fixPluralTypo($class, true), $data);
        $data = str_replace('MODELNAME', self::fixPluralTypo(ucfirst($name), true), $data);

        $storage = Storage::path(config('storage.repositories'));

        if (!is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        if (!$storage->writeFile(self::fixPluralTypo($class, true) . '.php', $data)) {
            return false;
        }
        
        return true;
    }
    
    public static function createHelper(string $helper)
    {
        list(, $class) = self::generateClass($helper, 'helper', true);

        $data = self::stubs()->readFile('Helper.stub');
        $data = str_replace('CLASSNAME', $class, $data);

        if (!Storage::path(config('storage.helpers'))->writeFile($class . '.php', $data)) {
            return false;
        }

        return true;
    }
    
    public static function createTest(string $test, bool $unit_test, ?string $path = null)
    {
        list(, $class) = self::generateClass($test, 'test', true);

        if ($unit_test) {
            $data = self::stubs()->addPath('tests')->readFile('UnitTest.stub');
        } else {
            $data = self::stubs()->addPath('tests')->readFile('ApplicationTest.stub');
        }

        $data = str_replace('CLASSNAME', $class, $data);

        $storage = Storage::path(config('storage.tests'));

        if ($unit_test) {
            $storage = $storage->addPath('Unit')->addPath($path ?? '');
        } else {
            $storage = $storage->addPath('Application')->addPath($path ?? '');
        }

        if (!$storage->writeFile($class . '.php', $data)) {
            return false;
        }
        
        return true;
    }
    
    public static function createValidator(string $validator, ?string $namespace = null)
    {
        list(, $class) = self::generateClass($validator, 'validator', true);

        $data = self::stubs()->readFile('Validator.stub');
        $data = self::addNamespace($data, 'App\Http\Validators', $namespace);
        $data = str_replace('CLASSNAME', $class, $data);

        $storage = Storage::path(config('storage.validators'));

        if (!is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        if (!$storage->writeFile($class . '.php', $data)) {
            return false;
        }

        return true;
    }
    
    public static function createMiddleware(string $middleware)
    {
        list(, $class) = self::generateClass($middleware, '', true);
        
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
    
    public static function createView(?string $view, ?string $layout, ?string $path = null)
    {
        $data = is_null($view) && !is_null($layout)
            ? self::stubs()->addPath('views')->readFile('layout.stub')
            : self::stubs()->addPath('views')->readFile('blank.stub');

        $data = str_replace('LAYOUTNAME', '{% extends "layouts/' . $layout . '.html.twig" %}', $data);
        $data = is_null($view) ? $data : str_replace('RESOURCENAME', $view, $data);
        
        $storage = Storage::path(config('storage.views'));

        if (is_null($view) && !is_null($layout)) {
            $storage = $storage->addPath('layouts');
        } else {
            $storage = $storage->addPath($path ?? '');
        }
        
        $view = is_null($view) && !is_null($layout) ? $layout : $view;

        if (!$storage->writeFile($view . '.html.twig', $data)) {
            return false;
        }
        
        return true;
    }
    
    public static function createConsole(string $console, string $command, string $description, ?string $namespace = null)
    {
        list(, $class) = self::generateClass($console, '', true);

        $data = self::stubs()->readFile('Console.stub');
        $data = self::addNamespace($data, 'App\Console', $namespace);
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('COMMANDNAME', $command, $data);
        $data = str_replace('COMMANDDESCPTION', $description, $data);

        $storage = Storage::path(config('storage.console'));

        if (!is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        if (!$storage->writeFile($class . '.php', $data)) {
            return false;
        }

        return true;
    }

    public static function createActions(string $model, ?string $namespace = null)
    {
        list($name, $class) = self::generateClass($model, 'actions', true, true);
        
        $data = self::stubs()->readFile('Actions.stub');
        $data = self::addNamespace($data, 'App\Http\Actions', $namespace);
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('$MODELNAME', '$' . self::fixPluralTypo($name, true), $data);
        $data = str_replace('MODELNAME', self::fixPluralTypo(ucfirst($name), true), $data);

        $storage = Storage::path(config('storage.actions'));

        if (!is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        if (!$storage->writeFile($class . '.php', $data)) {
            return false;
        }

        return true;
    }
}
