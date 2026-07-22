<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Shared;

use App\Database\Models\TokenModel;
use App\Enums\TokenDescription;
use App\Notifications\Mails\VerificationMail;
use Core\Notification\Notification;

final class NotifyUseCase
{
    public function handle(string $email): void
    {
        $tokenValue = generate_token(15);
        TokenModel::generate($email, TokenDescription::EMAIL_VERIFICATION->value, $tokenValue);

        Notification::send(new VerificationMail($email, $tokenValue))->to($email);
    }
}
