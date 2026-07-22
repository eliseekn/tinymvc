<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Factories;

use App\Database\Models\RoleModel;
use Core\Database\Factory\Factory;

class RoleFactory extends Factory
{
    public string $model = RoleModel::class;

    public function data(): array
    {
        return [
            'name' => faker()->word(),
        ];
    }
}
