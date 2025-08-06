<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Validation\Rules;

use Core\Http\Validation\Rule\RuleInterface;
use Somnambulist\Components\Validation\Rule;

class Password extends Rule implements RuleInterface
{
    public ?string $name = 'passowrd';

    public string $message = ':attribute must contains at least one uppercase letter, one lowercase letter, one special character (_-=+~!@#$%^&*) and one digit';

    public function check(mixed $value): bool
    {
        if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[-_=+~!@#$%^&*])[a-zA-Z\d\-_=+~!@#$%^&*]+$/', $value)) {
            return true;

        }

        return false;
    }
}
