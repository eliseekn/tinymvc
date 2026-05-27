<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Support;

use Core\Enums\AlertType;

/**
 * Manage alerts messages.
 */
class Alert
{
    protected static array $alert = [];

    public static function alert(string|array $message, string $display, bool $dismiss = true): self
    {
        self::$alert = [
            'message' => $message,
            'display' => $display,
            'dismiss' => $dismiss,
        ];

        return new self;
    }

    public static function default(string|array $message, bool $dismiss = true): self
    {
        return self::alert($message, AlertType::DEFAULT, $dismiss);
    }

    public static function toast(string|array $message, bool $dismiss = true): self
    {
        return self::alert($message, AlertType::TOAST, $dismiss);
    }

    public function success(): void
    {
        self::$alert['type'] = AlertType::SUCCESS;
        session()->create('alert', self::$alert);
    }

    public function error(): void
    {
        self::$alert['type'] = AlertType::ERROR;
        session()->create('alert', self::$alert);
    }

    public function info(): void
    {
        self::$alert['type'] = AlertType::INFO;
        session()->create('alert', self::$alert);
    }

    public function warning(): void
    {
        self::$alert['type'] = AlertType::WARNING;
        session()->create('alert', self::$alert);
    }
}
