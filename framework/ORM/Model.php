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
use Framework\Support\Pager;

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
    protected static $table = '';

    /**
     * find row by column id
     *
     * @param  int $id
     * @return mixed
     */
    public static function find(int $id)
    {
        return self::findWhere('id', '=', $id);
    }
    
    /**
     * fetch single row
     *
     * @param  string $column name of column
     * @param  string $operator operator
     * @param  string $value value to check
     * @return mixed
     */
    public static function findWhere(string $column, string $operator, string $value)
    {
        return QueryBuilder::DB()->select('*')
            ->from(static::$table)
            ->where($column, $operator, $value)
            ->fetchSingle();
    }

    /**
     * fetch all rows
     *
     * @param  array $order order by column and direction DESC or ASC
     * @return mixed
     */
    public static function findAll(array $order = ['id', 'DESC'])
    {
        return QueryBuilder::DB()->select('*')
            ->from(static::$table)
            ->orderBy($order[0], $order[1])
            ->fetchAll();
    }

    /**
     * find rows with where clause
     *
     * @param  string $column name of column
     * @param  string $operator operator
     * @param  string $value value to check
     * @param  array $order order by column and direction DESC or ASC
     * @return mixed
     */
    public static function findAllWhere(
        string $column,
        string $operator,
        string $value,
        array $order = ['id', 'DESC']
    ) {
        return QueryBuilder::DB()->select('*')
            ->from(static::$table)
            ->where($column, $operator, $value)
            ->orderBy($order[0], $order[1])
            ->fetchAll();
    }

    /**
     * find rows with limit clause
     *
     * @param  int $limit
     * @param  int $offset
     * @param  array $order_by order by column and direction DESC or ASC
     * @return mixed
     */
    public static function findRange(int $limit, int $offset, array $order_by = ['id', 'DESC'])
    {
        return QueryBuilder::DB()->select('*')
            ->from(static::$table)
            ->orderBy($order_by[0], $order_by[1])
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
     * @param  array $order_by order by column and direction DESC or ASC
     * @return mixed
     */
    public static function findRangeWhere(
        int $limit,
        int $offset,
        string $column,
        string $operator,
        string $value,
        array $order_by = ['id', 'DESC']
    ) {
        return QueryBuilder::DB()->select('*')
            ->from(static::$table)
            ->where($column, $operator, $value)
            ->orderBy($order_by[0], $order_by[1])
            ->limit($limit, $offset)
            ->fetchAll();
    }
    
    /**
     * delete row with WHERE clause
     *
     * @param  string $column name of column
     * @param  string $operator operator
     * @param  string $value value to check
     * @return void
     */
    public static function deleteWhere(string $column, string $operator, string $value): void
    {
        QueryBuilder::DB()->deleteFrom(static::$table)
            ->where($column, $operator, $value)
            ->executeQuery();
    }
    
    /**
     * delete row
     *
     * @param  int $id row id
     * @return void
     */
    public static function delete(int $id): void
    {
        self::deleteWhere('id', '=', $id);
    }
    
    /**
     * update data with where clause
     *
     * @param  string $column name of column
     * @param  string $operator operator
     * @param  string $value value to check
     * @param  array $data data to update
     * @return void
     */
    public function updateWhere(string $column, string $operator, string $value, array $data): void
    {
        QueryBuilder::DB()->update(static::$table)
            ->set($data)
            ->where($column, $operator, $value)
            ->executeQuery();
    }
    
    /**
     * update data
     *
     * @param  array $data data to update
     * @param  int $id row id
     * @return void
     */
    public function update(int $id, array $data): void
    {
        self::updateWhere('id', '=', $id, $data);
    }
    
    /**
     * insert data
     *
     * @param  array $data data to insert
     * @return void
     */
    public function insert(array $data): void
    {
        QueryBuilder::DB()->insert(static::$table, $data)
            ->executeQuery();
    }
    
    /**
     * generate pagination
     *
     * @param  mixed $items_per_pages
     * @param  array $order_by order by column and direction DESC or ASC
     * @return mixed returns new pager class instance
     */
    public static function paginate(int $items_per_pages, array $order_by = ['id', 'DESC'])
    {
        $page = empty(Request::getQuery('page')) ? 1 : Request::getQuery('page');

        $total_items = QueryBuilder::DB()->select('*')
            ->from(static::$table)
            ->rowsCount();

        $pagination = generate_pagination($page, $total_items, $items_per_pages);

        $items = $items_per_pages > 0 ? 
            self::findRange($pagination['first_item'], $items_per_pages, $order_by) :
            self::findAll($order_by);

        return new Pager($items, $pagination);
    }

    /**
     * generate pagination with where clause
     *
     * @param  int $items_per_pages
     * @param  string $column name of column
     * @param  string $operator operator
     * @param  string $value value to check
     * @param  array $order_by order by column and direction DESC or ASC
     * @return mixed returns new pager class instance
     */
    public static function paginateWhere(
        int $items_per_pages,
        string $column,
        string $operator,
        string $value,
        array $order_by = ['id', 'DESC']
    ) {
        $page = empty(Request::getQuery('page')) ? 1 : Request::getQuery('page');

        $total_items = QueryBuilder::DB()->select('*')
            ->from(static::$table)
            ->where($column, $operator, $value)
            ->rowsCount();

        $pagination = generate_pagination($page, $total_items, $items_per_pages);

        $items = $items_per_pages > 0 ? 
            self::findRangeWhere(
                $pagination['first_item'],
                $items_per_pages,
                $column,
                $operator,
                $value,
                $order_by
            ) : 
            self::findAllWhere($column, $operator, $value, $order_by);

        return new Pager($items, $pagination);
    }
}