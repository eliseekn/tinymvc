<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Validation\Rules;

use Core\Database\Repository;
use Core\Http\Validation\Rule\RuleInterface;
use Somnambulist\Components\Validation\Rule;

class Exists extends Rule implements RuleInterface
{
    public ?string $name = 'exists';

    public string $message = ':attribute does not exists';

    public array $fillableParams = ['table', 'column'];

    public function check(mixed $value): bool
    {
        $this->assertHasRequiredParameters(['table', 'column']);

        return (new Repository($this->params['table']))
            ->select($this->params['column'])
            ->where($this->params['column'], $value)
            ->exists();
    }
}
