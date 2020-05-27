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

namespace Framework\ORM;

use Framework\Http\Request;
use Framework\Support\Paginator;

/**
 * Model
 * 
 * Simplify use of query builder
 */
class Model
{    
    /**
     * name of table
     *
     * @var string
     */
    protected $table = ''; 
    
    /**
     * data to insert or update in table
     *
     * @var array
     */
    protected $data = [];
    
    /**
     * query builder instance
     *
     * @var mixed
     */
    protected $QB;
    
    /**
     * request instance variable
     *
     * @var mixed
     */
    protected $request;

    /**
     * instantiates class
     *
     * @return void
     */
    public function __construct(string $table)
    {
        $this->QB = new QueryBuilder();
        $this->table = $table;
        $this->request = new Request();
    }
    
    /**
     * find row by id column
     *
     * @param  mixed $id
     * @return void
     */
    public function find(int $id)
    {
        return $this->findSingle('id', '=', $id);
    }
    
    /**
     * fetch single row
     *
     * @param  string $column name of column
     * @param  string $operator operator
     * @param  string $value value to check
     * @return void
     */
    public function findSingle(string $column, string $operator, string $value)
    {
        return (object) $this->QB->select('*')
            ->from($this->table)
            ->where($column, $operator, $value)
            ->fetchSingle();
    }

    /**
     * fetch all rows
     *
     * @param  string $direction DESC or ASC
     * @return void
     */
    public function findAll(string $direction = 'DESC')
    {
        $items = $this->QB->select('*')
            ->from($this->table)
            ->orderBy('id', $direction)
            ->fetchAll();

        //convert array to class
        $items = array_map(
            function ($val) {
                return (object) $val;
            },
            (array) $items
        );

        return (object) $items;
    }

    /**
     * find rows with where clause
     *
     * @param  string $column name of column
     * @param  string $operator operator
     * @param  string $value value to check
     * @param  string $direction DESC or ASC
     * @return void
     */
    public function findAllWhere(string $column, string $operator, string $value, string $direction = 'DESC')
    {
        $items = $this->QB->select('*')
            ->from($this->table)
            ->where($column, $operator, $value)
            ->orderBy('id', $direction)
            ->fetchAll();

        //convert array to class
        $items = array_map(
            function ($val) {
                return (object) $val;
            },
            (array) $items
        );

        return (object) $items;
    }

    /**
     * find rows with limit clause
     *
     * @param  int $limit
     * @param  int $offset
     * @param  string $direction DESC or ASC
     * @return void
     */
    public function findRange(int $limit, int $offset, string $direction = 'DESC')
    {
        $items = $this->QB->select('*')
            ->from($this->table)
            ->orderBy('id', $direction)
            ->limit($limit, $offset)
            ->fetchAll();
        
        //convert array to class
        $items = array_map(
            function ($val) {
                return (object) $val;
            },
            (array) $items
        );

        return (object) $items;
    }

    /**
     * find rows with limit and where clauses
     *
     * @param  int $limit
     * @param  int $offset
     * @param  string $column name of column
     * @param  string $operator operator
     * @param  string $value value to check
     * @param  string $direction DESC or ASC
     * @return void
     */
    public function findRangeWhere(
        int $limit,
        int $offset,
        string $column,
        string $operator,
        string $value,
        string $direction = 'DESC'
    ) {
        $items = $this->QB->select('*')
            ->from($this->table)
            ->where($column, $operator, $value)
            ->orderBy('id', $direction)
            ->limit($limit, $offset)
            ->fetchAll();
        
        //convert array to class
        $items = array_map(
            function ($val) {
                return (object) $val;
            },
            (array) $items
        );

        return (object) $items;
    }
    
    /**
     * delete row
     *
     * @param  int $id row id
     * @return void
     */
    public function delete(int $id): void
    {
        $this->QB->deleteFrom($this->table)
            ->where('id', '=', $id)
            ->limit(1)
            ->executeQuery();
    }
    
    /**
     * set data to update or insert
     *
     * @param  array $data data value
     * @return void
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }
    
    /**
     * update 
     *
     * @param  int $id row id
     * @return void
     */
    public function update(int $id): void
    {
        $this->QB->update($this->table)
            ->set($this->data)
            ->where('id', '=', $id)
            ->executeQuery();
    }
    
    /**
     * save
     *
     * @return void
     */
    public function save(): void
    {
        $this->QB->insert($this->table, $this->data)
            ->executeQuery();
    }
    
    /**
     * generate pagination
     *
     * @param  mixed $page current page
     * @param  mixed $items_per_pages
     * @return mixed returns paginator class
     */
    public function paginate(int $items_per_pages)
    {
        $page = empty($this->request->getQuery('page')) ? 1 : (int) $this->request->getQuery('page');

        $total_items = $this->QB->select('*')
            ->from($this->table)
            ->rowsCount();

        $pagination = generate_pagination($page, $total_items, $items_per_pages);

        $items = $items_per_pages > 0 ? 
            $this->findRange($pagination['first_item'], $items_per_pages) :
            $this->findAll();

        return new Paginator($items, $pagination);
    }

    /**
     * generate pagination with where clause
     *
     * @param  int $items_per_pages
     * @param  string $column name of column
     * @param  string $operator operator
     * @param  string $value value to check
     * @return mixed returns new paginator class
     */
    public function paginateWhere(
        int $items_per_pages,
        string $column,
        string $operator,
        string $value
    ) {
        $page = empty($this->request->getQuery('page')) ? 1 : (int) $this->request->getQuery('page');

        $total_items = $this->QB->select('*')
            ->from($this->table)
            ->rowsCount();

        $pagination = generate_pagination($page, $total_items, $items_per_pages);

        $items = $items_per_pages > 0 ? 
            $this->findRangeWhere(
                $items_per_pages,
                $pagination['first_item'],
                $column,
                $operator,
                $value
            ) : 
            $this->findAllWhere($column, $operator, $value);

        return new Paginator($items, $pagination);
    }
}