<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\PasswordReset;

use App\Database\Models\TokenModel;
use App\Enums\TokenDescription;
use Core\Http\Auth\Auth;

final class UpdatePasswordUseCase
{
    public function handle(array $data): bool
    {
        $provider = $data['provider'] ?? 'user';

        if (! Auth::hasProvider($provider)) {
            return false;
        }

        $token = TokenModel::findByDescription($data['email'], TokenDescription::PASSWORD_RESET->value, $provider);

        if (! $token || $token->getValue() !== $data['token'] || $token->isExpired()) {
            return false;
        }

        $entity = config('security.auth.providers.'.$provider.'.entity');

        $account = (new $entity)->toModel()->findBy('email', $data['email'])?->toEntity($entity);

        if (! $account) {
            return false;
        }

        $account->setPassword($data['password']);

        if (! (bool) $account->toModel()->save()) {
            return false;
        }

        $token->toModel()->delete();

        return true;
    }
}
