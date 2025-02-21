<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Api\v1;

use Core\Routing\Controller;

class UserController extends Controller
{
    public function __invoke(): void
	{
        $this->response('Hello from UsercontrollerController');
	}
}
