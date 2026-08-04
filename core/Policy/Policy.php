<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Policy;

use Core\Database\Model;
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;
use Core\Enums\RouteName;
use Core\Http\Response\JsonResponse;
use Core\Http\Response\ViewResponse;

final class Policy
{
    public function __construct(private PolicyInterface $policy, private Model $model) {}

    public static function authorize(PolicyInterface $policy, Model $model): self
    {
        return new self($policy, $model);
    }

    public function onIndex(): void
    {
        $this->handle(RouteName::INDEX);
    }

    public function onCreate(): void
    {
        $this->handle(RouteName::CREATE);
    }

    public function onStore(): void
    {
        $this->handle(RouteName::STORE);
    }

    public function onUpdate(): void
    {
        $this->handle(RouteName::UPDATE);
    }

    public function onEdit(): void
    {
        $this->handle(RouteName::EDIT);
    }

    public function onShow(): void
    {
        $this->handle(RouteName::SHOW);
    }

    public function onDelete(): void
    {
        $this->handle(RouteName::DELETE);
    }

    private function handle(string $action): void
    {
        $result = call_user_func([$this->policy, $action], $this->model);

        if (! $result) {
            if (request()->isJson()) {
                new JsonResponse([
                    'status' => ResponseStatus::ERROR,
                    'message' => 'Unauthorized',
                ], HttpCode::FORBIDDEN)->send();
            }

            new ViewResponse(config('errors.views.403'))->send();
        }
    }
}
