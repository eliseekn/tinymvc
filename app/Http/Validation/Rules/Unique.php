<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Validation\Rules;

use Core\Database\Repository;
use Core\Exceptions\CoreException;
use Core\Http\Validation\Rule\RuleInterface;
use Somnambulist\Components\Validation\Rule;

class Unique extends Rule implements RuleInterface
{
    public ?string $name = 'unique';

    public string $message = ':attribute has already been used';

    public array $fillableParams = ['table', 'column', 'value'];

    public function check(mixed $value): bool
    {
        $this->assertHasRequiredParameters(['table']);

        $field = $this->attribute->key();
        $table = $this->params['table'];

        if (! isset($this->params['column'])) {
            return (new Repository($table))
                ->select($field)
                ->where($field, $value)
                ->notExists();
        }

        $column = $this->params['column'];

        $model = (new Repository($table))
            ->select([$column, $field])
            ->where($column, $this->params['value'])
            ->get();

        if (! $model) {
            throw new CoreException("Model not found for '$table'");
        }

        $existing = (new Repository($table))
            ->select([$column, $field])
            ->where($field, $value)
            ->get();

        if (! $existing || $model->get($column) === $existing->get($column)) {
            return true;
        }

        return false;
    }
}
