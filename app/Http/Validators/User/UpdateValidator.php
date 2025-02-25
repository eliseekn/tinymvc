<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Validators\User;

use App\Enums\UserRole;
use App\Http\Validators\Rules\Unique;
use Core\Http\Validator\Rule;
use Core\Http\Validator\Validator;

class UpdateValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return Rule::add('name', Rule::maxLen(255))
            ->add('email', [
                Rule::EMAIL,
                Rule::maxLen(255),
                Rule::custom(Unique::class, [
                    'users',
                    request()->route()[0]
                ]),
            ])
            ->add('role', Rule::in([UserRole::USER->value, UserRole::ADMIN->value]))
            ->get();
    }
}
