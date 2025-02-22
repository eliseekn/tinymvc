<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Validators;

use Core\Http\Validator\Rule;
use Core\Http\Validator\Validator;

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
        return Rule::add('name', Rule::MaxLen(255))
            ->add('email', [
                Rule::REQUIRED,
                Rule::EMAIL,
                Rule::MaxLen(255),
                'unique,users;' . auth()->get('id'),
            ])
            ->add('password', Rule::MaxLen(255))
            ->add('avatar', Rule::FileExtension(['png', 'jpg', 'jpeg']))
            ->get();
    }
}
