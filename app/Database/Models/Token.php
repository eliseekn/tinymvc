<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Models;

use Core\Database\Model;

class Token extends Model
{
    public static $table = 'tokens';

    public const PASSWORD_RESET_TOKEN = 'password_reset_token';
    public const EMAIL_VERIFICATION_TOKEN = 'email_verifications_token';

    public function __construct()
    {
        parent::__construct(static::$table);
    }
}
