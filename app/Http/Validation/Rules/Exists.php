<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Validation\Rules;

use Core\Database\Repository;
use Core\Http\Validation\Rule\RuleInterface;

class Exists implements RuleInterface
{
    public string $name = 'exists';

    public string $errorMessage = 'This {field} does not exists in database';

    public function rule(string $field, array $input, array $params, $value): bool
    {
        return (new Repository($params[0]))
            ->select('*')
            ->where($params[1], $value)
            ->exists();
    }
}
