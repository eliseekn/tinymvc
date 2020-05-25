<?php

namespace Framework\ORM;

use Framework\Http\Request;

/**
 * Paginator
 * 
 * Generate pagination from database 
 */
class Paginator
{    
    /**
     * database items
     *
     * @var mixed
     */
    protected $items;

    /**
     * generated pagination array
     *
     * @var array
     */
    protected $pagination = [];
    
    /**
     * request instance variable
     *
     * @var mixed
     */
    protected $request;
        
    /**
     * instantiates class
     *
     * @param  mixed $items database items
     * @param  array $pagination
     * @return void
     */
    public function __construct($items, array $pagination)
    {
        $this->items = $items;

        //add items as properties
        foreach ($items as $key => $value) {
            $this->{$key} = $value;
        }

        $this->pagination = $pagination;
        $this->request = new Request();
    }
    
    /**
     * get database items
     *
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }
    
    /**
     * returns current page number
     *
     * @return int
     */
    public function currentPage(): int
    {
        return $this->pagination['page'];
    }
    
    /**
     * returns previous page number
     *
     * @return int
     */
    public function previousPage(): int
    {
        return $this->pagination['page'] - 1;
    }
    
    /**
     * returns next page number
     *
     * @return int
     */
    public function nextPage(): int
    {
        return $this->pagination['page'] + 1;
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
        return absolute_url($this->request->getURI() . '?page=' . $this->previousPage());
    }
    
    /**
     * generate next page url
     *
     * @return string
     */
    public function nextPageUrl(): string
    {
        return absolute_url($this->request->getURI() . '?page=' . $this->nextPage());
    }
}