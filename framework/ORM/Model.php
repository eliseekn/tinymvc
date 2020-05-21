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
     * findId
     *
     * @param  mixed $id
     * @return void
     */
    public function find(int $id)
    {
        return $this->findWhere('id', '=', $id);
    }
    
    /**
     * findWhere
     *
     * @param  mixed $column
     * @param  mixed $operator
     * @param  mixed $value
     * @return void
     */
    public function findWhere(string $column, string $operator, string $value)
    {
        $this->QB->select('*')
            ->from($this->table)
            ->where($column, $operator, $value);
        
        return $this;
    }
    
    /**
     * retrieves single row
     *
     * @return void
     */
    public function single()
    {
        return (object) $this->Qb->fetchSingle();
    } 
        
    /**
     * retrieves all rows
     *
     * @return void
     */
    public function all()
    {
        return (object) $this->Qb->fetchAll();
    }
        
    /**
     * deleteWhere
     *
     * @param  mixed $column
     * @param  mixed $operator
     * @param  mixed $value
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
     * setData
     *
     * @param  mixed $data
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