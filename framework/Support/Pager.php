<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
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
     * database items
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

        //add items as properties
        foreach ($items as $key => $value) {
            $this->{$key} = $value;
        }
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
     * @param  bool $full_url
     * @return string
     */
    public function firstPageUrl(bool $full_url = false): string
    {
        return absolute_url(($full_url ? Request::getFullUri() : Request::getUri()) . '?page=1');
    }

    /**
     * generate previous page url
     *
     * @param  bool $full_url
     * @return string
     */
    public function previousPageUrl(bool $full_url = false): string
    {
        return absolute_url(($full_url ? Request::getFullUri() : Request::getUri()) . '?page=' . $this->previousPage());
    }
    
    /**
     * generate next page url
     *
     * @param  bool $full_url
     * @return string
     */
    public function nextPageUrl(bool $full_url = false): string
    {
        return absolute_url(($full_url ? Request::getFullUri() : Request::getUri()) . '?page=' . $this->nextPage());
    }

    /**
     * generate last page url
     *
     * @param  bool $full_url
     * @return string
     */
    public function lastPageUrl(bool $full_url = false): string
    {
        return absolute_url(($full_url ? Request::getFullUri() : Request::getUri()) . '?page=' . $this->totalPages());
    }
    
    /**
     * generate page url
     *
     * @param  int $page
     * @param  bool $full_url
     * @return string
     */
    public function pageUrl(int $page, bool $full_url = false): string
    {
        return absolute_url(($full_url ? Request::getFullUri() : Request::getUri()) . '?page=' . $page);
    }
}