<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Validation\Validators\User;

use App\Enums\UserRole;
use App\Http\Validation\Rules\Unique;
use Core\Http\Validation\Rule\Rule;
use Core\Http\Validation\Validator\Validator;

class StoreValidator extends Validator
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
        return Rule::add('name', [Rule::REQUIRED, Rule::maxLen(255)])
            ->add('email', [
                Rule::REQUIRED,
                Rule::EMAIL,
                Rule::maxLen(255),
                Rule::custom(Unique::class, 'users'),
            ])
            ->add('password', [
                Rule::REQUIRED,
                Rule::maxLen(255),
                Rule::minLen(8),
            ])
            ->add('role', [
                Rule::REQUIRED,
                Rule::in([UserRole::USER->value, UserRole::ADMIN->value]),
            ])
            ->make();
    }
}
