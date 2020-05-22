<?php

namespace Framework\ORM;

class Model
{    
    /**
     * name of table
     *
     * @var string
     */
    private $table = ''; 
    
    /**
     * data to insert or update in table
     *
     * @var array
     */
    private $data = [];
    
    /**
     * query builder instance
     *
     * @var mixed
     */
    private $QB;

    /**
     * instantiates class
     *
     * @return void
     */
    public function __construct(string $table)
    {
        $this->QB = new QueryBuilder();
        $this->table = $table;
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
     * find row with where clause and get single row
     *
     * @param  string $column name of column
     * @param  string $operator operator
     * @param  string $value value to check
     * @return void
     */
    public function findSingle(string $column, string $operator, string $value)
    {
        $this->QB->select('*')
            ->from($this->table)
            ->where($column, $operator, $value);

        return (object) $this->QB->fetchSingle();
    }

    /**
     * find row with where clause and get all rows
     *
     * @param  string $column name of column
     * @param  string $operator operator
     * @param  string $value value to check
     * @return void
     */
    public function findAll(string $column, string $operator, string $value)
    {
        $this->QB->select('*')
            ->from($this->table)
            ->where($column, $operator, $value);

        return (object) $this->QB->fetchAll();
    }
    
    /**
     * delete with where clause
     *
     * @param  string $column name of column
     * @param  string $operator operator
     * @param  string $value value to check
     * @return void
     */
    public function delete(int $id)
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
     * @param  mixed $id
     * @return void
     */
    public function update(int $id)
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
    public function save()
    {
        $this->QB->insert($this->table, $this->data)
            ->executeQuery();
    }
}