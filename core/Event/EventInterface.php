<?php

declare(strict_types=1);

namespace Core\Event;

interface EventInterface
{
    public function dispatch();
}
