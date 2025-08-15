<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Make;

use Core\Enums\NotificationType;
use Core\Support\Storage;

/**
 * Create templates from stubs.
 */
class Maker
{
    /**
     * Get stubs storage path.
     */
    private static function stubs(): Storage
    {
        return storage(config('storage.stubs'));
    }

    public static function removeUnderscore(string $word): string
    {
        if (str_contains($word, '_')) {
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

    public static function fixPlural(string $word, bool $remove = false): string
    {
        if (! $remove) {
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

    public static function generateClass(string $base_name, string $suffix = '', bool $singular = false, bool $force_singular = false): array
    {
        $name = ucfirst(strtolower($base_name));

        if (! $singular) {
            $name = self::fixPlural($name);
        }

        if ($force_singular) {
            $name = self::fixPlural($name, true);
        }

        $name = self::removeUnderscore($name);

        if ($suffix === 'migration') {
            return [lcfirst($name), 'Version'.date('YmdHis')];
        }

        $suffix = self::removeUnderscore($suffix);
        $class = $name.ucfirst($suffix);

        return [lcfirst($name), $class];
    }

    public static function createController(string $controller, ?string $namespace = null): bool
    {
        [, $class] = self::generateClass($controller, 'controller', true, true);

        $data = self::stubs()->readFile('Controller.stub');
        $data = self::addNamespace($data, 'App\Http\Controllers', $namespace);
        $data = str_replace('CLASSNAME', $class, $data);

        $storage = storage(config('storage.controllers'));

        if (! is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        return $storage->writeFile($class.'.php', $data);
    }

    public static function createModel(string $model, ?string $namespace = null): bool
    {
        [$name, $class] = self::generateClass($model);

        $data = self::stubs()->addPath('database')->readFile('Model.stub');
        $data = self::addNamespace($data, 'App\Database\Models', $namespace);
        $data = str_replace('CLASSNAME', self::fixPlural($class, true), $data);
        $data = str_replace('TABLE_NAME', $name, $data);

        $storage = storage(config('storage.models'));

        if (! is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        return $storage->writeFile(self::fixPlural($class, true).'.php', $data);
    }

    public static function createMigration(string $migration): bool
    {
        [$name, $class] = self::generateClass($migration, 'migration');

        $data = self::stubs()->addPath('database')->readFile('Migration.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('TABLE_NAME', $name, $data);

        return storage(config('storage.migrations'))->writeFile($class.'.php', $data);
    }

    public static function createSeeder(string $seeder): bool
    {
        [$name, $class] = self::generateClass($seeder, 'seeder', true, true);

        $data = self::stubs()->addPath('database')->readFile('Seeder.stub');
        $data = str_replace('CLASSNAME', self::fixPlural($class, true), $data);
        $data = str_replace('MODEL_NAME', self::fixPlural(ucfirst($name), true), $data);

        return storage(config('storage.seeders'))->writeFile(self::fixPlural($class, true).'.php', $data);
    }

    public static function createFactory(string $factory, ?string $namespace = null): bool
    {
        [$name, $class] = self::generateClass($factory, 'factory', true, true);

        $data = self::stubs()->addPath('database')->readFile('Factory.stub');
        $data = self::addNamespace($data, 'App\Database\Factories', $namespace);
        $data = str_replace('CLASSNAME', self::fixPlural($class, true), $data);
        $data = str_replace('MODEL_NAME', self::fixPlural(ucfirst($name), true), $data);

        $storage = storage(config('storage.factories'));

        if (! is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        return $storage->writeFile(self::fixPlural($class, true).'.php', $data);
    }

    public static function createEvent(string $event): bool
    {
        [, $class] = self::generateClass($event, singular: true, force_singular: true);
        $className = self::fixPlural($class.'Event', true);

        $data = self::stubs()->addPath('events')->readFile('Event.stub');
        $data = self::addNamespace($data, "App\Events\\".self::fixPlural($class, true));
        $data = str_replace('CLASSNAME', $className, $data);

        $storage = storage(config('storage.events'));
        $storage = $storage->addPath(self::fixPlural($class, true));

        return $storage->writeFile($className.'.php', $data);
    }

    public static function createListener(string $listener, string $event): bool
    {
        $data = self::stubs()->addPath('events')->readFile('Listener.stub');
        $data = self::addNamespace($data, "App\Events\\".$event);
        $data = str_replace('CLASSNAME', $listener, $data);
        $data = str_replace('EVENT', $event.'Event', $data);

        $storage = storage(config('storage.events'));
        $storage = $storage->addPath($event);

        return $storage->writeFile($listener.'.php', $data);
    }

    public static function createHelper(string $helper): bool
    {
        [, $class] = self::generateClass($helper, 'helper', true);

        $data = self::stubs()->readFile('Helper.stub');
        $data = str_replace('CLASSNAME', $class, $data);

        return storage(config('storage.helpers'))->writeFile($class.'.php', $data);
    }

    public static function createException(string $exception, string $message): bool
    {
        [, $class] = self::generateClass($exception, 'exception', true);

        $data = self::stubs()->readFile('Exception.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('MESSAGE', $message, $data);

        return storage(config('storage.exceptions'))->writeFile($class.'.php', $data);
    }

    public static function createEnum(string $enum): bool
    {
        [, $class] = self::generateClass($enum, singular: true);

        $data = self::stubs()->readFile('Enum.stub');
        $data = str_replace('ENUM_NAME', $class, $data);

        return storage(config('storage.enums'))->writeFile($class.'.php', $data);
    }

    public static function createTest(string $test, ?string $namespace = null): bool
    {
        [, $class] = self::generateClass($test, 'test', true);

        $data = self::stubs()->addPath('tests')->readFile('FeatureTest.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = self::addNamespace($data, 'Tests\Feature', $namespace);

        return storage(config('storage.tests'))
            ->addPath('Feature')->addPath($namespace ?? '')
            ->writeFile($class.'.php', $data);
    }

    public static function createUnitTest(string $test, ?string $namespace = null): bool
    {
        [, $class] = self::generateClass($test, 'test', true);

        $data = self::stubs()->addPath('tests')->readFile('UnitTest.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = self::addNamespace($data, 'Tests\Unit', $namespace);

        return storage(config('storage.tests'))
            ->addPath('Unit')->addPath($namespace ?? '')
            ->writeFile($class.'.php', $data);
    }

    public static function createBrowserTest(string $test, ?string $namespace = null): bool
    {
        [, $class] = self::generateClass($test, 'test', true);

        $data = self::stubs()->addPath('tests')->readFile('BrowserTest.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = self::addNamespace($data, 'Tests\Browser', $namespace);

        return storage(config('storage.tests'))
            ->addPath('Browser')->addPath($namespace ?? '')
            ->writeFile($class.'.php', $data);
    }

    public static function createValidator(string $validator, ?string $namespace = null): bool
    {
        [, $class] = self::generateClass($validator, 'validator', true);

        $data = self::stubs()->addPath('validators')->readFile('Validator.stub');
        $data = self::addNamespace($data, 'App\Http\Validation\Validators', $namespace);
        $data = str_replace('CLASSNAME', $class, $data);

        $storage = storage(config('storage.validators'));

        if (! is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        return $storage->writeFile($class.'.php', $data);
    }

    public static function createRule(string $rule): bool
    {
        [$name, $class] = self::generateClass($rule, singular: true);

        $data = self::stubs()->addPath('validators')->readFile('Rule.stub');
        $data = self::addNamespace($data, 'App\Http\Validation\Rules');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('RULE_NAME', strtolower($name), $data);

        $storage = storage(config('storage.rules'));

        return $storage->writeFile($class.'.php', $data);
    }

    public static function createMiddleware(string $middleware): bool
    {
        [, $class] = self::generateClass($middleware, singular: true);

        $data = self::stubs()->readFile('Middleware.stub');
        $data = str_replace('CLASSNAME', $class, $data);

        return storage(config('storage.middlewares'))->writeFile($class.'.php', $data);
    }

    public static function createNotification(string $notification, string $type): bool
    {
        [$name, $class] = self::generateClass($notification, $type, force_singular: true);

        $data = self::stubs()->addPath('notifications')->readFile(ucfirst($type).'.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('RESOURCE_NAME', $name, $data);

        if ($type === NotificationType::SMS) {
            return storage(config('storage.notifications.sms'))->writeFile($class.'.php', $data);
        }

        if (! storage(config('storage.notifications.mails'))->writeFile($class.'.php', $data)) {
            return false;
        }

        $data = self::stubs()->addPath('views')->readFile('email.stub');

        return storage(config('storage.views'))
            ->addPath('emails')
            ->writeFile($name.'.html.twig', $data);
    }

    public static function createView(?string $view, ?string $layout, ?string $path = null): bool
    {
        $data = is_null($view) && ! is_null($layout)
            ? self::stubs()->addPath('views')->readFile('layout.stub')
            : self::stubs()->addPath('views')->readFile('blank.stub');

        $data = str_replace('LAYOUT_NAME', '{% extends "layouts/'.$layout.'.html.twig" %}', $data);
        $data = is_null($view) ? $data : str_replace('RESOURCE_NAME', $view, $data);

        $storage = storage(config('storage.views'));

        $storage = is_null($view) && ! is_null($layout)
            ? $storage->addPath('layouts')
            : $storage->addPath($path ?? '');

        $view = is_null($view) && ! is_null($layout) ? $layout : $view;

        return $storage->writeFile($view.'.html.twig', $data);
    }

    public static function createConsole(string $console, string $command, string $description, ?string $namespace = null): bool
    {
        [, $class] = self::generateClass($console, '', true);

        $data = self::stubs()->readFile('Console.stub');
        $data = self::addNamespace($data, 'App\Console', $namespace);
        $data = str_replace('CLASSNAME', $class, $data);
        $data = str_replace('COMMAND_NAME', $command, $data);
        $data = str_replace('COMMAND_DESCRIPTION', $description, $data);

        $storage = storage(config('storage.console'));

        if (! is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        return $storage->writeFile($class.'.php', $data);
    }

    public static function createUseCase(string $type, ?string $model = null, ?string $namespace = null): bool
    {
        [$type, $class] = self::generateClass($type, 'use_case', true, true);
        $class = str_replace(['Index', 'Show'], ['GetCollection', 'GetItem'], $class);

        if (is_null($model)) {
            $type = 'blank';
        }

        $data = self::stubs()->addPath('useCases')->readFile($type.'.stub');

        if (! is_null($model)) {
            [$name] = self::generateClass($model, 'use_case', true, true);
            $namespace = is_null($namespace) ? ucfirst($name) : $namespace.'\\'.ucfirst($name);
            $data = str_replace('$MODEL_NAME', '$'.self::fixPlural($name, true), $data);
            $data = str_replace('MODEL_NAME', self::fixPlural(ucfirst($name), true), $data);
        }

        $data = self::addNamespace($data, 'App\Http\UseCases', $namespace);
        $data = str_replace('CLASSNAME', $class, $data);

        $storage = storage(config('storage.useCases'));

        if (! is_null($namespace)) {
            $storage = $storage->addPath(str_replace('\\', '/', $namespace));
        }

        return $storage->writeFile($class.'.php', $data);
    }
}
