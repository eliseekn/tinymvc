<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Validation\Validators\User;

use App\Http\Validation\Rules\Exists;
use App\Http\Validation\Rules\Password;
use App\Http\Validation\Rules\Unique;
use Core\Http\Validation\Rule\Rules;
use Core\Http\Validation\Validator\Validator;

class StoreValidator extends Validator
{
    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return Rules::add('name', [Rules::required(), Rules::max(255)])
            ->add('email', [
                Rules::required(),
                Rules::email(),
                Rules::max(255),
                Rules::custom(new Unique, 'users'),
            ])
            ->add('password', [
                Rules::required(),
                Rules::between(8, 10),
                Rules::custom(new Password),
            ])
            ->add('role_id', [
                Rules::required(),
                Rules::custom(new Exists, ['roles', 'id']),
            ])
            ->make();
    }
}
