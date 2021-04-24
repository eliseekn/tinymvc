<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Support;

use Framework\Http\Request;

/**
 * Generate pagination from database 
 */
class Pager
{
    /**
     * generated pagination array
     *
     * @var array $pagination
     */
    protected $pagination = [];

    /**
     * pages items
     * 
     * @var array $items
     */
    protected $items = [];

    /**
     * instantiates class
     *
     * @param  mixed $items
     * @param  array $pagination
     * @return void
     */
    public function __construct($items, array $pagination)
    {
        $this->pagination = $pagination;
        $this->items = $items;
    }
    
    /**
     * get items array
     *
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }
    
    /**
     * set items array
     *
     * @return void
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }
    
    /**
     * get page first item
     *
     * @return int
     */
    public function getFirstItem(): int
    {
        return $this->pagination['first_item'];
    }
    
    /**
     * get total items
     *
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->pagination['total_items'];
    }
    
    /**
     * get page total items
     *
     * @return int
     */
    public function getPageTotalItems(): int
    {
        return count($this->items);
    }
    
    /**
     * get items count per pages
     *
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->pagination['items_per_pages'];
    }
    
    /**
     * get current page number
     *
     * @return int
     */
    public function currentPage(): int
    {
        if ($this->pagination['page'] < 1) {
            return 1;
        }
        
        if ($this->pagination['page'] > $this->totalPages()) {
            return $this->totalPages();
        }

        return $this->pagination['page'];
    }
    
    /**
     * get previous page number
     *
     * @return int
     */
    public function previousPage(): int
    {
        return $this->currentPage() - 1;
    }
    
    /**
     * get next page number
     *
     * @return int
     */
    public function nextPage(): int
    {
        return $this->currentPage() + 1;
    }
    
    /**
     * check if pagination has less pages
     *
     * @return bool
     */
    public function hasLess(): bool
    {
        return $this->currentPage() > 1;
    }
    
    /**
     * check if pagination has more pages
     *
     * @return bool
     */
    public function hasMore(): bool
    {
        return $this->currentPage() < $this->totalPages();
    }
    
    /**
     * get total pages
     *
     * @return int
     */
    public function totalPages(): int
    {
        return $this->pagination['total_pages'];
    }

    /**
     * generate first page url
     *
     * @return string
     */
    public function firstPageUrl(): string
    {
        return url($this->generateUri(1));
    }

    /**
     * generate previous page url
     *
     * @return string
     */
    public function previousPageUrl(): string
    {
        return url($this->generateUri($this->previousPage()));
    }
    
    /**
     * generate next page url
     *
     * @return string
     */
    public function nextPageUrl(): string
    {
        return url($this->generateUri($this->nextPage()));
    }

    /**
     * generate last page url
     *
     * @return string
     */
    public function lastPageUrl(): string
    {
        return url($this->generateUri($this->totalPages()));
    }
    
    /**
     * generate page url
     *
     * @param  int $page
     * @return string
     */
    public function pageUrl(int $page): string
    {
        return url($this->generateUri($page));
    }
    
    /**
     * generate uri with queries or not
     *
     * @param  mixed $page
     * @return string
     */
    private function generateUri(int $page): string
    {
        $request = new Request();
        $uri = $request->fullUri();

        if (strpos($uri, '?')) {
            $queries = substr($uri, strpos($uri, '?'), strlen($uri));
        }

        if (!isset($queries)) {
            $uri = $request->uri() . '?page=' . $page;
        } else {
            if (strpos($queries, '&page=')) {
                $queries = substr($queries, strpos($queries, '?'), -1);
                $uri = $request->uri() . $queries . $page;
            } else if (strpos($queries, '?page=')) {
                $queries = substr($queries, strpos($queries, '?'), -1);
                $uri = $request->uri() . $queries . $page;
            } else  {
                $uri = $request->uri() . $queries . '&page=' . $page;
            }
        }

        return $uri;
    }
}