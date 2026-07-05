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
            'name' => $model->getAttributes('name'),
            'email' => $model->getAttributes('email'),
            'avatar' => is_null($model->getAttributes('avatar')) ? null : storage_url('uploads/'.$model->getAttributes('avatar')),
            'role' => $model->belongsTo('roles')->getAttributes('name'),
            'created_at' => carbon($model->getAttributes('created_at'))->locale(config('app.lang'))->isoFormat('Do MMM YYYY'),
        ];
    }
}
