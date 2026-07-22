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
            ->add('password', [
                Rules::required(),
                Rules::between(8, 10),
                Rules::custom(new Password),
            ])
            ->make();
    }
}
