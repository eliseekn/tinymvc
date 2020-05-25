<?php

namespace Framework\ORM;

use Framework\Http\Request;

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
        return (object) $this->QB->select('*')
            ->from($this->table)
            ->orderBy('id', $direction)
            ->fetchAll();
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
        return (object) $this->QB->select('*')
            ->from($this->table)
            ->where($column, $operator, $value)
            ->orderBy('id', $direction)
            ->fetchAll();
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
        return (object) $this->QB->select('*')
            ->from($this->table)
            ->orderBy('id', $direction)
            ->limit($limit, $offset)
            ->fetchAll();
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
        return (object) $this->QB->select('*')
            ->from($this->table)
            ->where($column, $operator, $value)
            ->orderBy('id', $direction)
            ->limit($limit, $offset)
            ->fetchAll();
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
     * @return array
     */
    public function paginate(int $items_per_pages)
    {
        $page = empty($this->request->getQuery('page')) ? 1 : (int) $this->request->getQuery('page');

        $total_items = $this->QB->select('*')
            ->from($this->table)
            ->rowsCount();

        $pagination = generate_pagination($page, $total_items, $items_per_pages);
        $items = $this->findRange($items_per_pages, $pagination['first_item']);

        dump_exit(count((array) $items), $pagination['first_item']);

        //convert array to class
        $items = array_map(
            function ($val) {
                return (object) $val;
            },
            (array) $items
        );

        

        return new Paginator($items, $pagination);
    }
}