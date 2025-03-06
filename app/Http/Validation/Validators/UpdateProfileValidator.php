<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Validation\Validators;

use App\Http\Validation\Rules\Unique;
use Core\Http\Validation\Rule\Rule;
use Core\Http\Validation\Validator\Validator;

class UpdateProfileValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return Rule::add('name', Rule::maxLen(255))
            ->add('email', [
                Rule::EMAIL,
                Rule::maxLen(255),
                Rule::custom(Unique::class, [
                    'users',
                    auth()->get('id'),
                ]),
            ])
            ->add('password', Rule::maxLen(255))
            ->make();
    }
}
