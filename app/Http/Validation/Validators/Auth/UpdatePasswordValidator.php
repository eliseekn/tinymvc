<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Validation\Validators\Auth;

use App\Http\Validation\Rules\Password;
use Core\Http\Validation\Rule\Rules;
use Core\Http\Validation\Validator\Validator;

class UpdatePasswordValidator extends Validator
{
    /**
     * Validation rules
     */
    public function rules(): array
    {
        return Rules::add('email', [Rules::required(), Rules::email()])
            ->add('token', Rules::required())
            ->add('provider', [Rules::sometimes(), Rules::in(array_keys(array_diff_key(config('security.auth.providers'), ['default' => 0])))])
            ->add('password', [
                Rules::required(),
                Rules::between(8, 15),
                Rules::custom(new Password),
            ])
            ->make();
    }
}
