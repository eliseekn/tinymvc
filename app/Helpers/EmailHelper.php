<?php

namespace App\Helpers;

use Framework\Support\Email;

class EmailHelper
{    
    /**
     * send welcome email notification
     *
     * @param  string $email
     * @return bool
     */
    public static function sendWelcome(string $email): bool
    {
        return Email::to($email)
            ->from(config('mailer.from'), config('mailer.name'))
            ->replyTo(config('mailer.from'), config('mailer.name'))
			->subject('Welcome')
            ->html('
                <p>Hello,</p>
                <p>Congratulations, your account has been created successfully.</p>
            ')
			->send();
    }
    
    /**
     * send password reset token link notification
     *
     * @param  string $email
     * @param  string $token
     * @return bool
     */
    public static function sendToken(string $email, string $token): bool
    {
        return Email::to($email)
            ->from(config('mailer.from'), config('mailer.name'))
            ->replyTo(config('mailer.from'), config('mailer.name'))
            ->subject('Password reset notification')
            ->html('
                <p>You are seeing this email because we received a password reset request for your account. Click the link below to reset your password:</p>
                <p><a href="' . absolute_url('/password/reset?email=' . $email . '&token=' . $token) . '">' . absolute_url('/password/reset?email=' . $email . '&token=' . $token) . '</a></p>
                <p>If you did not requested a password reset, no further action is required.</p>
            ')
            ->send();
    }

    /**
     * send email confirmation
     *
     * @param  string $email
     * @param  string $token
     * @return bool
     */
    public static function sendConfirmation(string $email, string $token): bool
    {
        return Email::to($email)
            ->from(config('mailer.from'), config('mailer.name'))
            ->replyTo(config('mailer.from'), config('mailer.name'))
			->subject('Email confirmation')
            ->html('
                <p>You are seeing this email because you have registered an account to ' . config('app.name') . '. Click the link below to confirm your email email:</p>
                <p><a href="' . absolute_url('/email/confirm?email=' . $email . '&token=' . $token) . '">' . absolute_url('/email/confirm?email=' . $email . '&token=' . $token) . '</a></p>
                <p>If you did not registered an account, no further action is required.</p>
            ')
			->send();
    }

    /**
     * send authentication email
     *
     * @param  string $email
     * @param  string $app site or application name
     * @return bool
     */
    public static function sendAuth(string $email, string $token): bool
    {
        return Email::to($email)
            ->from(config('mailer.from'), config('mailer.name'))
            ->replyTo(config('mailer.from'), config('mailer.name'))
			->subject('Two-Factor authentication')
            ->html('
                <p>You are seeing this email because you have requested connexion to your account on ' . config('app.name') . '. Click the link below to process log in:</p>
                <p><a href="' . absolute_url('/email/auth?email=' . $email . '&token=' . $token) . '">' . absolute_url('/email/auth?email=' . $email . '&token=' . $token) . '</a></p>
                <p>If you did not registered an account, no further action is required.</p>
            ')
			->send();
    }
}
