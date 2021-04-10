<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Routing;

use Exception;
use ReflectionClass;

/**
 * Auto dependancy injection class
 * 
 * @link https://indigotree.co.uk/automatic-dependency-injection-with-phps-reflection-api/
 */
class ReflectionResolver
{
    /**
 	* Build an instance of the given class
	* 
 	* @param string $class
 	* @param string|null $method
    * @param array $paramns
 	* @return void
 	*
 	* @throws Exception
 	*/
	public function resolve(string $class, ?string $method = null, array $params = []): void
	{
        $reflector = new ReflectionClass($class);
        $parameters = [];

        foreach ($reflector->getMethods() as $m) {
            if ($m->name === $method) {
                $parameters += $m->getParameters();
            }
        }

        $dependencies = $this->getDependencies($parameters);

        call_user_func_array([$this->resolveClass($class), $method], array_merge($dependencies, $params));
	}

    /**
 	* Build an instance of the given class
	* 
 	* @param string $class
 	* @return mixed
 	*
 	* @throws Exception
 	*/
	public function resolveClass(string $class)
	{
		$reflector = new ReflectionClass($class);

		if (!$reflector->isInstantiable()) {
 			return;
 		}
		
 		$constructor = $reflector->getConstructor();
		
 		if (is_null($constructor)) {
 			return new $class;
 		}
		
 		$parameters = $constructor->getParameters();
 		$dependencies = $this->getDependencies($parameters);
		
 		return $reflector->newInstanceArgs($dependencies);
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
				$dependencies[] = $this->resolveClass($dependency->name);
			}
		}
		
		return $dependencies;
	}
}