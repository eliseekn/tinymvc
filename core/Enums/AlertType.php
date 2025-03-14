<?php

declare(strict_types=1);

namespace Core\Enums;

enum AlertType: string
{
    public const DEFAULT = 'default';

    public const TOAST = 'toast';

    public const INFO = 'primary';

    public const SUCCESS = 'success';

    public const ERROR = 'danger';

    public const WARNING = 'warning';
}
