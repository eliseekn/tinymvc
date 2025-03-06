<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Validation\Rules;

use Core\Database\Repository;
use Core\Exceptions\InvalidSQLQueryException;
use Core\Exceptions\ModelNotFoundException;
use Core\Http\Validation\Rule\RuleInterface;

class Unique implements RuleInterface
{
    public string $name = 'unique';

    public string $errorMessage = 'This {field} is already registered';

    /**
     * @throws InvalidSQLQueryException
     * @throws ModelNotFoundException
     */
    public function rule(string $field, array $input, array $params, $value): bool
    {
        if (! isset($params[1])) {
            return ! (new Repository($params[0]))
                ->select($field)
                ->where($field, $value)
                ->first();
        }

        $model = (new Repository($params[0]))
            ->select($field)
            ->where('id', $params[1])
            ->first();

        if (! $model) {
            throw new ModelNotFoundException($params[0]);
        }

        return $model->get($field) === $value;
    }
}
