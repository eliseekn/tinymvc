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

    public static function default($message, bool $dismiss = true): self
    {
        self::$alert = [
            'message' => $message,
            'display' => AlertType::DEFAULT,
            'dismiss' => $dismiss,
        ];

        return new self;
    }

    public static function toast($message, bool $dismiss = true): self
    {
        self::$alert = [
            'message' => $message,
            'display' => AlertType::TOAST,
            'dismiss' => $dismiss,
        ];

        return new self;
    }

    public function success(): void
    {
        self::$alert += ['type' => AlertType::SUCCESS];
        session()->create('alert', self::$alert);
    }

    public function error(): void
    {
        self::$alert += ['type' => AlertType::ERROR];
        session()->create('alert', self::$alert);
    }

    public function info(): void
    {
        self::$alert += ['type' => AlertType::INFO];
        session()->create('alert', self::$alert);
    }

    public function warning(): void
    {
        self::$alert += ['type' => AlertType::WARNING];
        session()->create('alert', self::$alert);
    }
}
