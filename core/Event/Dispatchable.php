<?php

declare(strict_types=1);

namespace Core\Event;

trait Dispatchable
{
    public function dispatch(): void
    {
        Event::dispatch(self::class, $this);
    }
}
