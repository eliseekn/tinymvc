<?php

declare(strict_types=1);

namespace App\Database\Seeders;

class Seeders
{
    public static function get(): array
    {
        return [
            RoleSeeder::class,
            UserSeeder::class,
        ];
    }
}
