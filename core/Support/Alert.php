<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Support;

use Core\Enums\Alert\AlertStyle;
use Core\Enums\Alert\MessageType;

/**
 * Manage alerts messages.
 */
class Alert
{
    protected static array $alert = [];

    public static function default(string|array $message, bool $dismiss = true): self
    {
        self::$alert = [
            'message' => $message,
            'style' => AlertStyle::DEFAULT,
            'dismiss' => $dismiss,
        ];

        return new self;
    }

    public static function toast(string|array $message, bool $dismiss = true): self
    {
        self::$alert = [
            'message' => $message,
            'style' => AlertStyle::TOAST,
            'dismiss' => $dismiss,
        ];

        return new self;
    }

    public function success(): void
    {
        self::$alert['type'] = MessageType::SUCCESS;
        session()->create('alert', self::$alert);
    }

    public function error(): void
    {
        self::$alert['type'] = MessageType::ERROR;
        session()->create('alert', self::$alert);
    }

    public function info(): void
    {
        self::$alert['type'] = MessageType::INFO;
        session()->create('alert', self::$alert);
    }

    public function warning(): void
    {
        self::$alert['type'] = MessageType::WARNING;
        session()->create('alert', self::$alert);
    }
}
