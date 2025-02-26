<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Validators\Auth;

use App\Http\Validators\Rules\Unique;
use Core\Http\Validator\Rule;
use Core\Http\Validator\Validator;

class RegisterValidator extends Validator
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
            ->add('password', [Rule::REQUIRED, Rule::maxLen(255)])
            ->get();
    }
}
