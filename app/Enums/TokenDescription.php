<?php

declare(strict_types=1);

namespace App\Enums;

enum TokenDescription: string
{
    const PASSWORD_RESET = 'password_reset';

    const EMAIL_VERIFICATION = 'email_verification';

    const AUTHENTICATION = 'authentication';
}
