<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Response;

use Core\Enums\HttpCode;

/**
 * Send view HTTP response.
 */
class ViewResponse extends BaseResponse
{
    public function __construct(string $view, array $data = [])
    {
        parent::__construct();

        $this->view($view, $data)->setStatusCode(HttpCode::OK);
    }
}
