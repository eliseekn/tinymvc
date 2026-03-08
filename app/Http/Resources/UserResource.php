<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Resources;

use Core\Database\Model;
use Core\Http\Resource;

class UserResource extends Resource
{
    public function toArray(Model $model): array
    {
        return [
            'id' => $model->getId(),
            'name' => $model->get('name'),
            'email' => $model->get('email'),
            'avatar' => is_null($model->get('avatar')) ? null : storage_url('uploads/'.$model->get('avatar')),
            'role' => $model->belongsTo('roles')->get('name'),
            'created_at' => carbon($model->get('created_at'))->locale(config('app.lang'))->isoFormat('Do MMM YYYY'),
        ];
    }
}
