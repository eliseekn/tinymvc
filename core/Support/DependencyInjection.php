<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Support;

use Closure;
use Core\Database\Model;
use Core\Http\Cookies;
use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Session;
use Core\Http\Validation\Validator\Validator;
use ReflectionClass;
use ReflectionException;
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
     *
     * @throws ReflectionException
     * @throws \Exception
     */
    public function resolve(string $class, string $method, array $params = [], array $bindings = []): mixed
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

        $dependencies = $this->getDependencies($parameters, $bindings);

        return call_user_func_array([$class, $method], array_merge($dependencies, $params));
    }

    /**
     * Execute closure with dependencies and methods dependencies.
     *
     * @throws ReflectionException
     * @throws \Exception
     */
    public function resolveClosure(Closure $closure, array $params = [], array $bindings = []): mixed
    {
        $reflector = new ReflectionFunction($closure);
        $parameters = $reflector->getParameters();
        $dependencies = $this->getDependencies($parameters, $bindings);

        return call_user_func_array($closure, array_merge($dependencies, $params));
    }

    /**
     * Generate new instance of dependencies.
     *
     * @throws \Exception
     */
    public function getDependencies(array $parameters, array $bindings = []): array
    {
        $dependencies = [];
        $bindingKey = 0;

        /**
         * @var ReflectionParameter $parameter
         */
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getType();

            if (! is_null($dependency)) {
                // @phpstan-ignore-next-line
                $class = $dependency->getName();

                // @phpstan-ignore-next-line
                if (! $dependency->isBuiltin()) {
                    if (is_subclass_of($class, Validator::class)) {
                        $class = $class::make()->validate(new Request, new Response);
                    } elseif (is_subclass_of($class, UseCase::class)) {
                        $class = new $class(new Request, new Response, new Session, new Cookies);
                    } elseif ($class === Model::class) {
                        [$table, $column, $value] = array_values($bindings[$bindingKey]);
                        $class = (new $class($table))->findBy($column, $value);
                        $bindingKey++;
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
