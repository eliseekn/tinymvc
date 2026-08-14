<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Observer;

interface ObserverInterface
{
    public function onCreated();

    public function onUpdated();

    public function onDeleted();

    public function onSoftDeleted();

    public function onForceDeleted();
}
