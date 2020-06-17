<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Support;

use Framework\Http\Request;

/**
 * Pager
 * 
 * Generate pagination from database 
 */
class Pager
{
    /**
     * generated pagination array
     *
     * @var array
     */
    protected $pagination = [];
    
    /**
     * instantiates class
     *
     * @param  mixed $items database items
     * @param  array $pagination pagination parameters
     * @return void
     */
    public function __construct($items, array $pagination)
    {
        $this->pagination = $pagination;

        //add items as properties
        foreach ($items as $key => $value) {
            $this->{$key} = $value;
        }
    }
    
    /**
     * getFirstItem
     *
     * @return int
     */
    public function getFirstItem(): int
    {
        return $this->pagination['first_item'];
    }
    
    /**
     * getTotalItems
     *
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->pagination['total_items'];
    }
    
    /**
     * getItemsPerPage
     *
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->pagination['items_per_pages'];
    }
    
    /**
     * returns current page number
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
     * returns previous page number
     *
     * @return int
     */
    public function previousPage(): int
    {
        return $this->currentPage() - 1;
    }
    
    /**
     * returns next page number
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
     * returns total pages
     *
     * @return int
     */
    public function totalPages(): int
    {
        return $this->pagination['total_pages'];
    }

    /**
     * generate previous page url
     *
     * @return string
     */
    public function previousPageUrl(): string
    {
        return absolute_url(Request::getURI() . '?page=' . $this->previousPage());
    }
    
    /**
     * generate next page url
     *
     * @return string
     */
    public function nextPageUrl(): string
    {
        return absolute_url(Request::getURI() . '?page=' . $this->nextPage());
    }
    
    /**
     * generate page url
     *
     * @param  int $page page id
     * @return string
     */
    public function pageUrl(int $page): string
    {
        return absolute_url(Request::getURI() . '?page=' . $page);
    }
}