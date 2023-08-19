<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
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

    private static function removeUnderscore(string $word): string
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

    private static function addNamespace(string $data, string $base, ?string $namespace = null): string
    {
        return is_null($namespace)
            ? str_replace('NAMESPACE', $base, $data)
            : str_replace('NAMESPACE', "$base\\$namespace", $data);
    }

    public static function fixPluralTypo(string $word, bool $remove = false): string
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
    
    public static function generateClass(string $base_name, string $suffix = '', bool $singular = false, bool $force_singlular = false): array
    {
        $name = ucfirst(strtolower($base_name));

        if (!$singular) $name = self::fixPluralTypo($name);
        if ($force_singlular) $name = self::fixPluralTypo($name, true);

        $name = self::removeUnderscore($name);

        if ($suffix === 'migration') {
            $suffix = 'table';
        }

        $suffix = self::removeUnderscore($suffix);
        $class = $name . ucfirst($suffix);

        if (strpos($class, 'Table')) {
            $class .= date('_YmdHis');
        }

        return [lcfirst($name), $class];
    }
    
    public static function createController(string $controller, ?string $namespace = null): bool
    {
        list(, $class) = self::generateClass($controller, 'controller', true, true);

        $data = self::stubs()->readFile('Controller.stub');
        $data = self::addNamespace($data, 'App\Http\Controllers', $namespace);
        $data = str_replace('CLASSNAME', $class, $data);

        $storage = Storage::path(config('storage.controllers'));

        if (!is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        return $storage->writeFile($class . '.php', $data);
    }

    public static function createModel(string $model, ?string $namespace = null): bool
    {
        list($name, $class) = self::generateClass($model);

        $data = self::stubs()->addPath('database')->readFile('Model.stub');
        $data = self::addNamespace($data, 'App\Database\Models', $namespace);
        $data = str_replace('CLASSNAME', self::fixPluralTypo($class, true), $data);
        $data = str_replace('TABLENAME', $name, $data);

        $storage = Storage::path(config('storage.models'));

        if (!is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        return $storage->writeFile(self::fixPluralTypo($class, true) . '.php', $data);
    }
 
    public static function createMigration(string $migration): bool
    {
        list($name, $class) = self::generateClass($migration, 'migration');

        $data = self::stubs()->addPath('database')->readFile('Migration.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('TABLENAME', $name, $data);

        return Storage::path(config('storage.migrations'))->writeFile($class . '.php', $data);
    }

    public static function createSeed(string $seed): bool
    {
        list($name, $class) = self::generateClass($seed, 'seed', true, true);

        $data = self::stubs()->addPath('database')->readFile('Seed.stub');
        $data = str_replace('CLASSNAME', self::fixPluralTypo($class, true), $data);
        $data = str_replace('TABLENAME', self::fixPluralTypo($name), $data);

        return Storage::path(config('storage.seeds'))->writeFile(self::fixPluralTypo($class, true) . '.php', $data);
    }
    
    public static function createFactory(string $factory, ?string $namespace = null): bool
    {
        list($name, $class) = self::generateClass($factory, 'factory', true, true);

        $data = self::stubs()->addPath('database')->readFile('Factory.stub');
        $data = self::addNamespace($data, 'App\Database\Factories', $namespace);
        $data = str_replace('CLASSNAME', self::fixPluralTypo($class, true), $data);
        $data = str_replace('MODELNAME', self::fixPluralTypo(ucfirst($name), true), $data);

        $storage = Storage::path(config('storage.factories'));

        if (!is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        return $storage->writeFile(self::fixPluralTypo($class, true) . '.php', $data);
    }

    public static function createEvent(string $event): bool
    {
        list(, $class) = self::generateClass(base_name: $event, singular: true, force_singlular: true);
        $className = self::fixPluralTypo($class . 'Event', true);

        $data = self::stubs()->addPath('events')->readFile('Event.stub');
        $data = self::addNamespace($data, "App\Events\\" . self::fixPluralTypo($class, true));
        $data = str_replace('CLASSNAME', $className, $data);

        $storage = Storage::path(config('storage.events'));
        $storage = $storage->addPath(self::fixPluralTypo($class, true));

        return $storage->writeFile($className . '.php', $data);
    }

    public static function createListener(string $listener): bool
    {
        list(, $class) = self::generateClass(base_name: $listener, singular: true, force_singlular: true);
        $className = self::fixPluralTypo($class . 'EventListener', true);

        $data = self::stubs()->addPath('events')->readFile('Listener.stub');
        $data = self::addNamespace($data, "App\Events\\" . self::fixPluralTypo($class, true));
        $data = str_replace('CLASSNAME', $className, $data);

        $storage = Storage::path(config('storage.events'));
        $storage = $storage->addPath(self::fixPluralTypo($class, true));

        return $storage->writeFile($className . '.php', $data);
    }

    public static function createHelper(string $helper): bool
    {
        list(, $class) = self::generateClass($helper, 'helper', true);

        $data = self::stubs()->readFile('Helper.stub');
        $data = str_replace('CLASSNAME', $class, $data);

        return Storage::path(config('storage.helpers'))->writeFile($class . '.php', $data);
    }
    
    public static function createException(string $exception, string $message): bool
    {
        list(, $class) = self::generateClass($exception, 'exception', true);

        $data = self::stubs()->readFile('Exception.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('MESSAGE', $message, $data);

        return Storage::path(config('storage.exceptions'))->writeFile($class . '.php', $data);
    }
    
    public static function createTest(string $test, bool $unit_test, ?string $path = null): bool
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

        return $storage->writeFile($class . '.php', $data);
    }
    
    public static function createValidator(string $validator, ?string $namespace = null): bool
    {
        list(, $class) = self::generateClass($validator, 'validator', true);

        $data = self::stubs()->addPath('validators')->readFile('Validator.stub');
        $data = self::addNamespace($data, 'App\Http\Validators', $namespace);
        $data = str_replace('CLASSNAME', $class, $data);

        $storage = Storage::path(config('storage.validators'));

        if (!is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        return $storage->writeFile($class . '.php', $data);
    }

    public static function createRule(string $rule): bool
    {
        list($name, $class) = self::generateClass(base_name: $rule, singular: true);

        $data = self::stubs()->addPath('validators')->readFile('Rule.stub');
        $data = self::addNamespace($data, 'App\Http\Validators\Rules');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('RULENAME', strtolower($name), $data);

        $storage = Storage::path(config('storage.rules'));
        return $storage->writeFile($class . '.php', $data);
    }

    public static function createMiddleware(string $middleware): bool
    {
        list(, $class) = self::generateClass($middleware, '', true);
        
        $data = self::stubs()->readFile('Middleware.stub');
        $data = str_replace('CLASSNAME', $class, $data);

        return Storage::path(config('storage.middlewares'))->writeFile($class . '.php', $data);
    }
    
    public static function createMail(string $mail): bool
    {
        list($name, $class) = self::generateClass($mail, 'mail');

        $data = self::stubs()->readFile('Mail.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('RESOURCENAME', $name, $data);

        if (!Storage::path(config('storage.mails'))->writeFile($class . '.php', $data)) {
            return false;
        }

        $data = self::stubs()->addPath('views')->readFile('email.stub');

        return Storage::path(config('storage.views'))
            ->addPath('emails')
            ->writeFile($name . '.html.twig', $data);
    }
    
    public static function createView(?string $view, ?string $layout, ?string $path = null): bool
    {
        $data = is_null($view) && !is_null($layout)
            ? self::stubs()->addPath('views')->readFile('layout.stub')
            : self::stubs()->addPath('views')->readFile('blank.stub');

        $data = str_replace('LAYOUTNAME', '{% extends "layouts/' . $layout . '.html.twig" %}', $data);
        $data = is_null($view) ? $data : str_replace('RESOURCENAME', $view, $data);
        
        $storage = Storage::path(config('storage.views'));

        $storage = is_null($view) && !is_null($layout)
            ? $storage->addPath('layouts')
            : $storage->addPath($path ?? '');

        $view = is_null($view) && !is_null($layout) ? $layout : $view;

        return $storage->writeFile($view . '.html.twig', $data);
    }
    
    public static function createConsole(string $console, string $command, string $description, ?string $namespace = null): bool
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

        return $storage->writeFile($class . '.php', $data);
    }

    public static function createAction(string $model, string $type, ?string $namespace = null): bool
    {
        list($name,) = self::generateClass($model, 'action', true, true);
        list($type, $class) = self::generateClass($type, 'action', true, true);

        $namespace = is_null($namespace) ? ucfirst($name) : $namespace . '\\' . ucfirst($name);
        $class = str_replace(['Index', 'Show'], ['GetCollection', 'GetItem'], $class);

        if (in_array($type, ['index', 'show', 'store', 'update', 'destroy'])) {
            $data = self::stubs()->addPath('actions')->readFile($type . '.stub');
        } else {
            $data = self::stubs()->addPath('actions')->readFile('blank.stub');
        }

        $data = self::addNamespace($data, 'App\Http\Actions', $namespace);
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('$MODELNAME', '$' . self::fixPluralTypo($name, true), $data);
        $data = str_replace('MODELNAME', self::fixPluralTypo(ucfirst($name), true), $data);

        $storage = Storage::path(config('storage.actions'));
        $storage = $storage->addPath(str_replace('\\', '/', $namespace));

        return $storage->writeFile($class . '.php', $data);
    }
}
