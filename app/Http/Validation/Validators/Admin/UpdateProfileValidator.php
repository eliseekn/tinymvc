<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Validation\Validators\Admin;

use App\Http\Validation\Rules\Password;
use App\Http\Validation\Rules\Unique;
use Core\Http\Validation\Rule\Rules;
use Core\Http\Validation\Validator\Validator;

class UpdateProfileValidator extends Validator
{
    /**
     * Validation rules
     */
    public function rules(): array
    {
        $id = (int) auth('admin')?->getId();

        return Rules::add('username', [
            Rules::sometimes(),
            Rules::max(255),
            Rules::custom(new Unique, ['admins', 'id', $id]),
        ])
            ->add('email', [
                Rules::sometimes(),
                Rules::email(),
                Rules::max(255),
                Rules::custom(new Unique, ['admins', 'id', $id]),
            ])
            ->add('password', [
                Rules::sometimes(),
                Rules::between(8, 10),
                Rules::custom(new Password),
            ])
            ->add('avatar', [
                Rules::sometimes(),
                Rules::uploadedFile(),
                Rules::extension(['png', 'jpg', 'jpeg']),
            ])
            ->make();
    }
}
