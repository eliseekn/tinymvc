<?php

namespace App\Helpers;

use Framework\Support\Email;

class EmailHelper
{    
    /**
     * send welcome email notification
     *
     * @param  string $address
     * @return bool
     */
    public static function sendWelcome(string $address): bool
    {
        return Email::to($address)
            ->from(EMAIL['from'], EMAIL['name'])
            ->replyTo(EMAIL['from'], EMAIL['name'])
			->subject('Welcome')
            ->message('
                <p>Hello,</p>
                <p>Congratulations, your account has been successfully created.</p>
            ')
			->asHTML()
			->send();
    }
    
    /**
     * send password reset token link notification
     *
     * @param  mixed $address
     * @param  mixed $token
     * @return bool
     */
    public static function sendToken(string $address, string $token): bool
    {
        return Email::to($address)
            ->from(EMAIL['from'], EMAIL['name'])
            ->replyTo(EMAIL['from'], EMAIL['name'])
            ->subject('Password reset notification')
            ->message('
                <p>You are receiving this email because we received a password reset request for your account. Click the button below to reset your password:</p>
                <p><a href="' . absolute_url('/password/reset?email=' . $address . '&token=' . $token) . '">' . absolute_url('/password/reset?email=' . $address . '&token=' . $token) . '</a></p>
                <p>If you did not request a password reset, no further action is required.</p>
            ')
            ->asHTML()
            ->send();
    }
}
