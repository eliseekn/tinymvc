<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Response;

use Core\Enums\HttpCode;

/**
 * Send Json HTTP response.
 */
class JsonResponse extends BaseResponse
{
    public function __construct(array $data, int $code = HttpCode::OK)
    {
        parent::__construct();

        $this->json($data)->setStatusCode($code);
    }
}
