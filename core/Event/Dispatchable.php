<?php

namespace Core\Event;

trait Dispatchable
{
    public function dispatch(): void
    {
        Event::dispatch(self::class, $this);
    }
}