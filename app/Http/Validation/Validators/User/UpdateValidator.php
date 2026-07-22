<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Validation\Validators\User;

use App\Http\Validation\Rules\Exists;
use App\Http\Validation\Rules\Unique;
use Core\Http\Validation\Rule\Rules;
use Core\Http\Validation\Validator\Validator;

class UpdateValidator extends Validator
{
    /**
     * Validation rules.
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
            ->add('role_id', [
                Rules::sometimes(),
                Rules::required(),
                Rules::custom(new Exists, ['roles', 'id']),
            ])
            ->make();
    }
}
