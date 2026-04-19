<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Validation\Validators\Role;

use Core\Http\Validation\Rule\Rules;
use Core\Http\Validation\Validator\Validator;

class UpdateValidator extends Validator
{
    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return Rules::add('name', [Rules::sometimes(), Rules::max(255)])->make();
    }
}
