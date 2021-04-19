<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\System;

use Exception;
use ReflectionClass;

/**
 * Auto dependancy injection class
 * 
 * @link https://indigotree.co.uk/automatic-dependency-injection-with-phps-reflection-api/
 *       https://dev.to/fadymr/php-auto-dependency-injection-with-reflection-api-27ci
 */
class DependcyInjection
{
    /**
 	* Build an instance of the given class
	* 
 	* @param string $class
 	* @param string|null $method
    * @param array $params
 	* @return void
 	*
 	* @throws Exception
 	*/
	public function resolve(string $class, ?string $method = null, array $params = []): void
	{

        $reflector = new ReflectionClass($class);
        $constructor = $reflector->getConstructor();

        $dependencies = [];

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

        $methods_dependencies = $this->getDependencies($parameters);

        call_user_func_array([$class, $method], array_merge($methods_dependencies, $params));
	}

	/**
	 * Build up a list of dependencies for a given methods parameters
	 *
	 * @param array $parameters
	 * @return array
	 */
	public function getDependencies(array $parameters): array
	{
		$dependencies = [];

		foreach($parameters as $parameter) {
			$dependency = $parameter->getClass();

            if (!is_null($dependency)) {
                $class = $dependency->getName();
				$dependencies[] = new $class();
			}
		}
		
		return $dependencies;
	}
}