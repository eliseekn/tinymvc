<?php

declare(strict_types=1);

namespace App\Enums;

enum TokenDescription: string
{
    public const PASSWORD_RESET = 'password_reset';

    public const EMAIL_VERIFICATION = 'email_verification';
    public const AUTHENTICATION = 'authentication';
}
