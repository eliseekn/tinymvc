<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Notification;

use Core\Support\Storage;

class FakeNotification
{
    protected static function storage(): Storage
    {
        return storage(config('storage.tmp'));
    }

    public static function load(string $name, string|array $recipient): void
    {
        $data[$name] = parse_array($recipient);

        static::storage()->writeFile('notifications.json', json_encode($data));
        static::storage()->writeFile('sent_notifications.json', '');
    }

    public static function send(string|array $name, string|array $recipient): void
    {
        $names = parse_array($name);
        $sent = static::sentNotifications();

        foreach ($names as $_name) {
            if (array_key_exists($_name, static::notifications())) {
                $sent[$_name] = $recipient;
            }
        }

        static::storage()->writeFile('sent_notifications.json', json_encode($sent), true);
    }

    public static function notifications(): array
    {
        if (! static::storage()->isFile('notifications.json')) {
            return [];
        }

        $data = static::storage()->readFile('notifications.json');

        if (empty($data)) {
            return [];
        }

        return json_decode($data, true);
    }

    public static function sentNotifications(): array
    {
        if (! static::storage()->isFile('sent_notifications.json')) {
            return [];
        }

        $data = static::storage()->readFile('sent_notifications.json');

        if (empty($data)) {
            return [];
        }

        return json_decode($data, true);
    }

    public static function clear(): void
    {
        static::storage()->deleteFile('notifications.json');
        static::storage()->deleteFile('sent_notifications.json');
    }
}
