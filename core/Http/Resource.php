<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http;

use Core\Database\Model;
use Core\Support\Pagination;

class Resource
{
    public function __construct(protected Model|Pagination|array $resource) {}

    public function toArray(Model $model): array
    {
        return [];
    }

    public function handle(): array
    {
        if ($this->resource instanceof Model) {
            return $this->toArray($this->resource);
        }

        if (is_array($this->resource)) {
            foreach ($this->resource as $item) {
                $data[] = $this->toArray($item);
            }

            return $data;
        }

        $items = $this->resource->getItems();
        $data = [];

        foreach ($items as $item) {
            $data[] = $this->toArray($item);
        }

        return [
            'data' => $data,
            'meta' => $this->resource->getMeta(),
        ];
    }
}
