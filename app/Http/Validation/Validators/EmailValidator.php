<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Validation\Validators;

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
            Rules::REQUIRED,
            Rules::EMAIL,
            Rules::max(255),
        ])
            ->make();
    }
}
