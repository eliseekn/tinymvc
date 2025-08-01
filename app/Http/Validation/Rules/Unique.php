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
use Core\Exceptions\ModelNotFoundException;
use Core\Http\Validation\Rule\RuleInterface;
use Somnambulist\Components\Validation\Rule;

class Unique extends Rule implements RuleInterface
{
    public ?string $name = 'unique';

    public string $message = ':attribute has already been used';

    public array $fillableParams = ['table', 'column'];

    public function check(mixed $value): bool
    {
        $this->assertHasRequiredParameters(['table']);

        $field = $this->attribute->key();

        if (! isset($this->params['column'])) {
            return ! (new Repository($this->params['table']))
                ->select($field)
                ->where($field, $value)
                ->exists();
        }

        $model = (new Repository($this->params['table']))
            ->select($field)
            ->where('id', $this->params['column'])
            ->first();

        if (! $model) {
            throw new ModelNotFoundException($this->params['table']);
        }

        return $model->get($field) === $value;
    }
}
