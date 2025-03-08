<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Support;

class AliasLoader
{
    protected array $classes = [];

    protected function loadClass($class): void
    {
        if (isset($this->classes[$class]) && class_exists($this->classes[$class])) {
            class_alias($this->classes[$class], $class);
        }
    }

    public function register(): self
    {
        $classes = require storage()->addPath('vendor/composer')->file('autoload_classmap.php');

        foreach ($classes as $class => $file) {
            $alias = explode('\\', $class);
            $alias = end($alias);

            if (! isset($this->classes[$alias])) {
                $this->classes[$alias] = $class;
            }
        }

        spl_autoload_register([$this, 'loadClass']);

        return $this;
    }

    public function unregister(): void
    {
        spl_autoload_unregister([$this, 'loadClass']);
    }
}