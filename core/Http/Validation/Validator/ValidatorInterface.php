<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Validation\Validator;

use Core\Http\Request;
use Core\Http\Response;

interface ValidatorInterface
{
    public function validate(Request $request, Response $response);

    public function failed();

    public function errors();

    public function inputs();

    public function rules();

    public function messages();

    public function validationSucceeded(Request $request, ?Response $response = null);

    public function validationFailed(Request $request, ?Response $response = null);
}
