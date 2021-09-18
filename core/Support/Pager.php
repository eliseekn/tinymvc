<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

use Core\Http\Request;

/**
 * Generate pagination from database 
 */
class Pager
{
    protected $pagination = [];
    protected $items = [];

    public function __construct(int $total_items, int $items_per_page, int $page = 1)
    {
        $this->pagination = [
			'page' => $page,
            'first_item' => ($page - 1) * $items_per_page,
			'total_items' => $total_items,
			'items_per_page' => $items_per_page,
			'total_pages' => $items_per_page > 0 ? ceil($total_items / $items_per_page) : 1
        ];
    }

    public function getItems(): array
    {
        return $this->items;
    }
    
    public function setItems(array $items): self
    {
        $this->items = $items;
        return $this;
    }
    
    public function getFirstItem()
    {
        return $this->pagination['first_item'];
    }
    
    public function getTotalItems()
    {
        return $this->pagination['total_items'];
    }
    
    public function getPageTotalItems()
    {
        return count($this->items);
    }
    
    public function getItemsPerPage()
    {
        return $this->pagination['items_per_page'];
    }
    
    public function currentPage()
    {
        if ($this->pagination['page'] < 1) return 1;
        if ($this->pagination['page'] > $this->totalPages()) return $this->totalPages();

        return $this->pagination['page'];
    }
    
    public function previousPage()
    {
        return $this->currentPage() - 1;
    }
    
    public function nextPage()
    {
        return $this->currentPage() + 1;
    }
    
    /**
     * Check if pagination has less pages
     */
    public function hasLess()
    {
        return $this->currentPage() > 1;
    }
    
    /**
     * Check if pagination has more pages
     */
    public function hasMore()
    {
        return $this->currentPage() < $this->totalPages();
    }
    
    public function totalPages()
    {
        return $this->pagination['total_pages'];
    }

    public function firstPageUrl()
    {
        return url($this->generateUri(1));
    }

    public function previousPageUrl()
    {
        return url($this->generateUri($this->previousPage()));
    }
    
    public function nextPageUrl()
    {
        return url($this->generateUri($this->nextPage()));
    }

    public function lastPageUrl()
    {
        return url($this->generateUri($this->totalPages()));
    }
    
    public function pageUrl(int $page)
    {
        return url($this->generateUri($page));
    }
    
    /**
     * Generate uri with queries or not
     */
    private function generateUri(int $page)
    {
        $request = new Request();
        $uri = $request->fullUri();

        if (strpos($uri, '?')) {
            $queries = substr($uri, strpos($uri, '?'), strlen($uri));
        }

        if (!isset($queries)) {
            $uri = $request->uri() . '?page=' . $page;
        } 
        
        else {
            if (strpos($queries, '&page=')) {
                $queries = substr($queries, strpos($queries, '?'), -1);
                $uri = $request->uri() . $queries . $page;
            } elseif (strpos($queries, '?page=')) {
                $queries = substr($queries, strpos($queries, '?'), -1);
                $uri = $request->uri() . $queries . $page;
            } else  {
                $uri = $request->uri() . $queries . '&page=' . $page;
            }
        }

        return $uri;
    }
}