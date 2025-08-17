<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
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
    protected array $pagination = [];

    protected array $items = [];

    public function __construct(int $total_items, int $items_per_page, int $page = 1)
    {
        $this->pagination = [
            'current_page' => $page,
            'first_item' => ($page - 1) * $items_per_page,
            'total_items' => $total_items,
            'items_per_page' => $items_per_page,
            'total_pages' => $items_per_page > 0 ? ceil($total_items / $items_per_page) : 1,
        ];
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getItemsAsArray(): array
    {
        $items = array_map(fn (Model $model) => $model->get(), $this->items);

        return [
            'data' => $items,
            'pagination' => array_merge(
                $this->pagination, [
                    'first_page_url' => $this->firstPageUrl(),
                    'last_page_url' => $this->lastPageUrl(),
                    'current_page_url' => $this->pageUrl($this->pagination['current_page']),
                    'next_page_url' => $this->nextPageUrl(),
                    'previous_page_url' => $this->previousPageUrl(),
                ]
            ),
        ];
    }

    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function getFirstItem(): int
    {
        return $this->pagination['first_item'];
    }

    public function getTotalItems(): int
    {
        return $this->pagination['total_items'];
    }

    public function getPageTotalItems(): int
    {
        return count($this->items);
    }

    public function getItemsPerPage(): int
    {
        return $this->pagination['items_per_page'];
    }

    public function currentPage(): int
    {
        if ($this->pagination['current_page'] < 1) {
            return 1;
        }

        if ($this->pagination['current_page'] > $this->totalPages()) {
            return $this->totalPages();
        }

        return $this->pagination['current_page'];
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
        return (int) $this->pagination['total_pages'];
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
