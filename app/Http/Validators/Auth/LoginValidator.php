<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Validators\Auth;

use Core\Http\Validator\Rule;
use Core\Http\Validator\Validator;

class LoginValidator extends Validator
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
        return Rule::add('email', [Rule::REQUIRED, Rule::EMAIL, Rule::MaxLen(255)])
            ->add('password', [Rule::REQUIRED, Rule::MaxLen(255)])
            ->get();
    }
}
