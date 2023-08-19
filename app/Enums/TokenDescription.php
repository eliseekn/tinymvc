<?php

namespace App\Enums;

enum TokenDescription: string
{
    case PASSWORD_RESET_TOKEN = 'password_reset_token';
    case EMAIL_VERIFICATION_TOKEN = 'email_verification_token';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}