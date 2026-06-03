<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Validation\Validators;

use App\Http\Validation\Rules\Password;
use App\Http\Validation\Rules\Unique;
use Core\Http\Validation\Rule\Rules;
use Core\Http\Validation\Validator\Validator;

class UpdateProfileValidator extends Validator
{
    public function authorize(): bool
    {
        return (int) request()->routeParam('user') === auth()->getId();
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return Rules::add('name', [Rules::sometimes(), Rules::max(255)])
            ->add('email', [
                Rules::sometimes(),
                Rules::required(),
                Rules::email(),
                Rules::max(255),
                Rules::custom(new Unique, [
                    'users',
                    'id',
                    (int) request()->routeParam('user'),
                ]),
            ])
            ->add('password', [
                Rules::sometimes(),
                Rules::required(),
                Rules::between(8, 10),
                Rules::custom(new Password),
            ])
            ->make();
    }
}
