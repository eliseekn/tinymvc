<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Response;

/**
 * Send redirect HTTP response.
 */
class RedirectResponse extends BaseResponse
{
    public function __construct()
    {
        parent::__construct();
    }

    public function toBack(): BaseResponse
    {
        return $this->back()->setStatusCode();
    }

    public function toUrl(string $uri, array $queries = []): BaseResponse
    {
        return $this->url($uri, $queries)->setStatusCode();
    }

    public function toRoute(string $route, array $params = []): BaseResponse
    {
        return $this->route($route, $params)->setStatusCode();
    }
}
