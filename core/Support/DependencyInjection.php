<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

use Closure;
use Core\Http\Cookies;
use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Session;
use Core\Http\Validator\Validator;
use ReflectionClass;
use ReflectionFunction;
use ReflectionParameter;

/**
 * Automatic dependancy injection class.
 *
 * @link https://indigotree.co.uk/automatic-dependency-injection-with-phps-reflection-api/
 *       https://dev.to/fadymr/php-auto-dependency-injection-with-reflection-api-27ci
 */
class DependencyInjection
{
    /**
     * Execute class with dependencies and methods dependencies.
     */
    public function resolve(string $class, string $method, array $params = []): mixed
    {
        $reflector = new ReflectionClass($class);
        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            $class = $reflector->newInstance();
        } else {
            $parameters = $constructor->getParameters();
            $dependencies = $this->getDependencies($parameters);
            $class = $reflector->newInstanceArgs($dependencies);
        }

        $parameters = [];

        foreach ($reflector->getMethods() as $methods) {
            if ($methods->name === $method) {
                $parameters += $methods->getParameters();
            }
        }

        $dependencies = $this->getDependencies($parameters);

        return call_user_func_array([$class, $method], array_merge($dependencies, $params));
    }

    /**
     * Execute closure with dependencies and methods dependencies.
     */
    public function resolveClosure(Closure $closure, array $params = []): mixed
    {
        $reflector = new ReflectionFunction($closure);
        $parameters = $reflector->getParameters();
        $dependencies = $this->getDependencies($parameters);

        return call_user_func_array($closure, array_merge($dependencies, $params));
    }

    /**
     * Generate new instance of dependencies.
     */
    public function getDependencies(array $parameters): array
    {
        $dependencies = [];

        /**
         * @var ReflectionParameter $parameter
         */
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getType();

            if (! is_null($dependency)) {
                $class = $dependency->getName();

                if (! $dependency->isBuiltin()) {
                    if (is_subclass_of($class, Validator::class)) {
                        $class = (new $class)->validate(new Request, new Response);
                    } elseif (is_subclass_of($class, UseCase::class)) {
                        $class = new $class(new Request, new Response, new Session, new Cookies);
                    } else {
                        $class = new $class;
                    }

                    $dependencies[] = $class;
                }
            }
        }

        return $dependencies;
    }
}
