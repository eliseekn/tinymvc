<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Validation\Validator;

interface ValidatorInterface
{
    public function validate();

    public function failed();

    public function errors();

    public function validated();

    public function rules();

    public function messages();

    public function validationSucceeded();

    public function validationFailed();

    public function beforeValidation();

    public function afterValidation();

    public static function make(array $rules = [], array $messages = []);
}
