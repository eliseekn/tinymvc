<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Support;

use Core\Database\Model;

/**
 * Generate pagination from database.
 */
class Pagination
{
    protected array $data = [];

    protected array $items = [];

    public function __construct(int $totalItems, int $itemsPerPage, int $page = 1)
    {
        $this->data = [
            'current_page' => $page,
            'first_item' => ($page - 1) * $itemsPerPage,
            'total_items' => $totalItems,
            'items_per_page' => $itemsPerPage,
            'total_pages' => $itemsPerPage > 0 ? ceil($totalItems / $itemsPerPage) : 1,
        ];
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getItemsAsArray(): array
    {
        $items = array_map(fn (Model $model) => $model->getAttributes(), $this->items);

        return [
            'data' => $items,
            'meta' => $this->getMeta(),
        ];
    }

    public function getMeta(): array
    {
        return array_merge(
            $this->data, [
                'first_page_url' => $this->firstPageUrl(),
                'last_page_url' => $this->lastPageUrl(),
                'current_page_url' => $this->pageUrl($this->data['current_page']),
                'next_page_url' => $this->nextPageUrl(),
                'previous_page_url' => $this->previousPageUrl(),
            ]
        );
    }

    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function getFirstItem(): int
    {
        return $this->data['first_item'];
    }

    public function getTotalItems(): int
    {
        return $this->data['total_items'];
    }

    public function getPageTotalItems(): int
    {
        return count($this->items);
    }

    public function getItemsPerPage(): int
    {
        return $this->data['items_per_page'];
    }

    public function currentPage(): int
    {
        if ($this->data['current_page'] < 1) {
            return 1;
        }

        if ($this->data['current_page'] > $this->totalPages()) {
            return $this->totalPages();
        }

        return $this->data['current_page'];
    }

    public function previousPage(): int
    {
        if ($this->currentPage() === 1) {
            return 1;
        }

        return $this->currentPage() - 1;
    }

    public function nextPage(): int
    {
        if ($this->totalPages() === $this->currentPage()) {
            return $this->totalPages();
        }

        return $this->currentPage() + 1;
    }

    /**
     * Check if pagination has less pages.
     */
    public function hasLess(): bool
    {
        return $this->currentPage() > 1;
    }

    /**
     * Check if pagination has more pages.
     */
    public function hasMore(): bool
    {
        return $this->currentPage() < $this->totalPages();
    }

    public function totalPages(): int
    {
        return (int) $this->data['total_pages'];
    }

    public function firstPageUrl(): string
    {
        return url($this->uri(1));
    }

    public function previousPageUrl(): string
    {
        return url($this->uri($this->previousPage()));
    }

    public function nextPageUrl(): string
    {
        return url($this->uri($this->nextPage()));
    }

    public function lastPageUrl(): string
    {
        return url($this->uri($this->totalPages()));
    }

    public function pageUrl(int $page): string
    {
        return url($this->uri($page));
    }

    /**
     * Generate pagination URI
     */
    private function uri(int $page): string
    {
        $uri = request()->fullUri();
        $queries = '';

        if (str_contains($uri, '?')) {
            $queries = substr($uri, strpos($uri, '?') + 1);
        }

        parse_str($queries, $queryArray);
        $queryArray['page'] = $page;

        return request()->uri().'?'.http_build_query($queryArray);
    }
}
