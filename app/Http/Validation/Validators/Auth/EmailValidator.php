<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Validation\Validators\Auth;

use Core\Http\Validation\Rule\Rules;
use Core\Http\Validation\Validator\Validator;

class EmailValidator extends Validator
{
    /**
     * Validation rules
     */
    public function rules(): array
    {
        return Rules::add('email', [
            Rules::required(),
            Rules::email(),
            Rules::max(255),
        ])
            ->add('provider', [Rules::sometimes(), Rules::in(array_keys(array_diff_key(config('security.auth.providers'), ['default' => 0])))])
            ->make();
    }
}
