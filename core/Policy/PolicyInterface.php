<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Policy;

use Core\Database\Entity;

interface PolicyInterface
{
    public function index();

    public function create();

    public function store();

    public function update(Entity $model);

    public function edit(Entity $model);

    public function show(Entity $model);

    public function delete(Entity $model);
}
